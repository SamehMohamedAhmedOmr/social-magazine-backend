<?php

namespace Modules\Catalogue\Services\CMS;

use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\ExcelExports\BrandExport;
use Modules\Catalogue\Repositories\CMS\BrandRepository;
use Modules\Catalogue\Transformers\CMS\Brand\BrandResource;
use Modules\Settings\Repositories\LanguageRepository;
use Modules\Catalogue\Transformers\Repo\BrandResource as CmsBrandResource;

class BrandService extends LaravelServiceClass
{
    private $brand_repo;
    private $language_repo;
    private $cache_key;

    public function __construct(BrandRepository $brand_repo, LanguageRepository $language_repo)
    {
        $this->brand_repo = $brand_repo;
        $this->language_repo = $language_repo;
        $this->cache_key = 'brand';
    }

    public function all()
    {
        $filters = $this->filters();
        $data = $this->brand_repo->index($filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);

        $brands = BrandResource::collection($this->brand_repo->loadRelations($data,[
            'currentLanguage',
            'brandImg.galleryType'
        ]));

        return ApiResponse::format(200, $brands);
    }

    public function index()
    {
        $filters = $this->filters();
        $data = $this->brand_repo->paginate($filters['per_page'], [], $filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $pagination = Pagination::preparePagination($data);
        $data = BrandResource::collection($this->brand_repo->loadRelations($data,[
            'brandImg.galleryType'
        ]));

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    protected function filters()
    {
        $search['search_key'] = request('search_key');
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['name', 'created_at'])
            ? request('sort_by') : 'id';
        $sort_language = Session::get('language_id');

        $search['trashed'] = request()->has('trashed')
            ? filter_var(request('trashed'), FILTER_VALIDATE_BOOLEAN) : null;

        $search['is_active'] = request()->has('is_active')
            ? filter_var(request('is_active'), FILTER_VALIDATE_BOOLEAN) : null;

        $per_page = request()->per_page ?: 15;

        return [
            'search' => $search,
            'order_by' => $order_by,
            'sort_by' => $sort_by,
            'per_page' => $per_page,
            'sort_language' => $sort_language,
        ];
    }

    public function show($brand_id)
    {
        $data = $this->brand_repo->get($brand_id);
        $data->load([
            'brandImg.galleryType'
        ]);
        $data = BrandResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function store()
    {
        $image_icon['icon'] = request('icon');

        $data = CmsBrandResource::toArray($this->language_repo, $this->brand_repo, $image_icon);
        $data = $this->brand_repo->create($data);
        $data->load([
            'brandImg.galleryType'
        ]);
        $data = BrandResource::make($data);
        return ApiResponse::format(201, $data, 'Successful Query');
    }

    public function delete($brand_id)
    {
        $this->brand_repo->delete($brand_id);
        return ApiResponse::format(204, [], 'Deleted Successfully');
    }

    public function update($brand_id)
    {
        $image_icon = [];

        $image_icon['icon'] = request('icon');

        $data = CmsBrandResource::toArray($this->language_repo, $this->brand_repo, $image_icon, $brand_id);
        $data = $this->brand_repo->update($brand_id, $data);
        $data->load([
            'brandImg.galleryType'
        ]);
        $data = BrandResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function removeImage($brand_id)
    {
        $image_icon = [];

        if (request()->has('icon')) {
            $image_icon['icon'] = null;
        }

        $data = $this->brand_repo->update($brand_id, $image_icon);
        $data = BrandResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function restore($brand_id)
    {
        $data = $this->brand_repo->restore($brand_id);
        $data = BrandResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Brands', \App::make(BrandExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
