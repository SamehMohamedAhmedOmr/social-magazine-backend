<?php

namespace Modules\Catalogue\Services\CMS;

use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\ExcelExports\ProductsExport;
use Modules\Catalogue\Repositories\CMS\ProductRepository;
use Modules\Catalogue\Transformers\CMS\Product\ProductResource;
use Modules\Settings\Repositories\LanguageRepository;
use Modules\Catalogue\Transformers\Repo\Facades\ProductResource as ProductRepoResource;

class ProductService extends LaravelServiceClass
{
    private $product_repo;
    private $language_repo;
    private $cache_key;

    public function __construct(ProductRepository $product_repo, LanguageRepository $language_repo)
    {
        $this->product_repo = $product_repo;
        $this->language_repo = $language_repo;
        $this->cache_key = 'product';
    }

    public function all()
    {
        $filters = $this->filters();
        $data = $this->product_repo->index($filters['order_by'], $filters['sort_by'],
            $filters['sort_language'], $filters['search']);

        // Important Loading for Export
        $data->load([
            'currentLanguage',
            'brand.currentLanguage',
            'mainCategory.currentLanguage',
            'categories.currentLanguage',
            'variations.currentLanguage',
            'unitOfMeasure.currentLanguage',
            'priceLists',
            'images'
        ]);

        if (isset($filters['search']['warehouses_ids'])) {
            Session::put('in_stock_warehouses', $filters['search']['warehouses_ids']);
            $data->load('inStockWarehouse');
            $data = ProductResource::collection($data);
        } else {
            $data = ProductResource::collection($data);
        }
        return ApiResponse::format(200, $data);
    }

    public function index()
    {
        $filters = $this->filters();
        $data = $this->product_repo->pagination($filters['per_page'], $filters['order_by'], $filters['sort_by'],
            $filters['sort_language'], $filters['search']);
        $pagination = Pagination::preparePagination($data);

        if (isset($filters['search']['warehouses_ids'])) {
            Session::put('in_stock_warehouses', $filters['search']['warehouses_ids']);
            $data = ProductResource::collection($this->product_repo->loadRelations($data, [
                'languages.language',
                'inStockWarehouse'
            ]));
        } else {
            $data = ProductResource::collection($this->product_repo->loadRelations($data));
        }

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }


    protected function filters()
    {
        $per_page = request('per_page') ?: 15;
        $search['search_key'] = request('search_key');

        ## Relations
        $search['category_ids'] = request()->has('category_ids') && !empty(request('category_ids'))
            ? (array) request('category_ids') : null;
        $search['brand_ids'] = request()->has('brand_ids') && !empty(request('brand_ids'))
            ? (array) request('brand_ids') : null;
        $search['warehouses_ids'] = request()->has('warehouses_ids') && !empty(request('warehouses_ids'))
            ? (array) request('warehouses_ids') : null;
        $search['parent_products'] = request()->has('parent_products') && !empty(request('parent_products')) // products.parent_id
            ? (array) request('parent_products') : null;
        $search['variant_values'] = request()->has('variant_values') && !empty(request('variant_values')) // product_variant_values
            ? (array) request('variant_values') : null;
        $search['topping_menus'] = request()->has('topping_menus') && !empty(request('topping_menus')) // product_topping_menu.product_id
            ? (array) request('topping_menus') : null;
        $search['min_price'] = request()->has('min_price') && request('min_price') != null // product_price_list.product_id
            ? (float) request('min_price') : null;
        $search['max_price'] = request()->has('max_price') && request('max_price') != null // product_price_list.product_id
            ? (float) request('max_price') : null;

        ## Booleans
        $search['trashed'] = request()->has('trashed') && request('trashed') != null
            ? filter_var(request('trashed'), FILTER_VALIDATE_BOOLEAN) : null;

        $search['is_active'] = request()->has('is_active') && request('is_active') != null
            ? filter_var(request('is_active'), FILTER_VALIDATE_BOOLEAN) : null;

        $search['is_sell_with_availability'] = request()->has('is_sell_with_availability') && request('is_sell_with_availability') != null
            ? filter_var(request('is_sell_with_availability'), FILTER_VALIDATE_BOOLEAN) : null;

        $search['is_variant'] = request()->has('is_variant') && request('is_variant') != null
            ? filter_var(request('is_variant'), FILTER_VALIDATE_BOOLEAN) : null;

        $search['is_topping'] = request()->has('is_topping') && request('is_topping') != null
            ? filter_var(request('is_topping'), FILTER_VALIDATE_BOOLEAN) : null;

        $search['is_bundle'] = request()->has('is_bundle') && request('is_bundle') != null
            ? filter_var(request('is_bundle'), FILTER_VALIDATE_BOOLEAN) : null;

        ## Order
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['name', 'sku', 'created_at'])
            ? request('sort_by') : 'id';
        $sort_language = Session::get('language_id');


        return [
            'search' => $search,
            'order_by' => $order_by,
            'sort_by' => $sort_by,
            'per_page' => $per_page,
            'sort_language' => $sort_language,
        ];
    }

    public function show($product_id)
    {
        $data = $this->product_repo->get($product_id);
        $data = ProductResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function store()
    {
        $data = ProductRepoResource::toArray(request());
        $data = $this->product_repo->create($data);
        $data = ProductResource::make($data);
        return ApiResponse::format(201, $data, 'Successful Query');
    }

    public function update($product_id)
    {
        $data = ProductRepoResource::toArray(request());
        $this->product_repo->update($product_id, $data);
        return $this->show($product_id);
    }


    public function delete($product_id)
    {
        $this->product_repo->delete($product_id);
        return ApiResponse::format(204, [], 'Deleted Successfully');
    }

    public function restore($product_id)
    {
        $data = $this->product_repo->restore($product_id);
        $data = ProductResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function storeImages($product_id, $images)
    {
        $product = $this->product_repo->get($product_id);

        $newImages = [];
        foreach ($images as $key => $image) {
            $order = $key +1;
            $newImages [] = [
                'image' => $image,
                'order' => $order
            ];
        }

        $this->product_repo->syncImages($product, $newImages);

        return ApiResponse::format(201, [], 'Images attached Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Products', \App::make(ProductsExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
