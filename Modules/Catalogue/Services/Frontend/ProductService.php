<?php

namespace Modules\Catalogue\Services\Frontend;

use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\Frontend\ProductRepository;
use Modules\Catalogue\Transformers\Frontend\ProductResource;
use Modules\Settings\Repositories\LanguageRepository;

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

    public function index()
    {
        $per_page = request('per_page') ?: 15;
        $search['search_key'] = request('search_key') ;

        ## Relations
        $search['categories'] = request()->has('categories') && !empty(request('categories'))
            ? explode(',', request('categories'))  : null;
        $search['collections'] = request()->has('collections') && !empty(request('collections'))
            ? explode(',', request('collections')) : null;
        $search['brands'] = request()->has('brands') && !empty(request('brands'))
            ? explode(',', request('brands')) : null;
        $search['parent_products'] = request()->has('parent_products') && !empty(request('parent_products')) // products.parent_id
            ? explode(',', request('parent_products')) : null;
        $search['variant_values'] = request()->has('variant_values') && !empty(request('variant_values')) // product_variant_value.product_id
            ? explode(',', request('variant_values')) : null;
        /*$search['topping_menus'] = request()->has('topping_menus') && !empty(request('topping_menus')) // product_topping_menu.product_id
            ? explode(',', request('topping_menus')) : null;*/
        $search['min_price'] = request()->has('min_price') && request('min_price') != null // product_price_list.product_id
            ? (float) request('min_price') : null;
        $search['max_price'] = request()->has('max_price') && request('max_price') != null // product_price_list.product_id
            ? (float) request('max_price') : null;

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
            ? request('sort_by') : 'created_at';
        $filter_language = Session::get('language_id');

        $data = $this->product_repo->pagination($per_page, [], $search, $sort_by, $order_by, $filter_language);
        $pagination = Pagination::preparePagination($data);

        $data = ProductResource::collection($this->product_repo->loadRelations($data));

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    public function show($slug)
    {
        $data = $this->product_repo->get($slug);
        $data = ProductResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }
}
