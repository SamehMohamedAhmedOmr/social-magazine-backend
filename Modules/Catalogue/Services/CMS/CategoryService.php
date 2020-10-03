<?php

namespace Modules\Catalogue\Services\CMS;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\ExcelExports\CategoryExport;
use Modules\Catalogue\Repositories\CMS\CategoryRepository;
use Modules\Catalogue\Transformers\CMS\Category\CategoryResource;
use Modules\Catalogue\Transformers\CMS\TryResource\CategoryResourceTry;
use Modules\Settings\Repositories\LanguageRepository;
use Modules\Catalogue\Transformers\Repo\CategoryResource as CmsCategoryResource;

class CategoryService extends LaravelServiceClass
{
    private $category_repo;
    private $language_repo;
    private $cache_key;

    public function __construct(CategoryRepository $category_repo, LanguageRepository $language_repo)
    {
        $this->category_repo = $category_repo;
        $this->language_repo = $language_repo;
        $this->cache_key = 'category';
    }


    public function all()
    {
        $filters = $this->filters();
        $data = $this->category_repo->index($filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $data =  CategoryResource::collection($this->category_repo->loadRelations($data,[
            'currentLanguage',
            'parent.currentLanguage',
            'categoryImg.galleryType',
            'categoryIcon.galleryType'
        ]));
        return ApiResponse::format(200, $data);
    }

    public function index()
    {
        $filters = $this->filters();
        $data = $this->category_repo->paginate($filters['per_page'], [], $filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $pagination = Pagination::preparePagination($data);
        $data = CategoryResource::collection($this->category_repo->loadRelations($data,[
            'categoryImg.galleryType',
            'categoryIcon.galleryType'
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

    public function show($category_id)
    {
        $data = $this->category_repo->get($category_id);
        $data->load([
            'categoryImg.galleryType',
            'categoryIcon.galleryType'
        ]);
        $data = CategoryResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function store()
    {
        $image_icon = [];

        $image_icon['image'] = request('image');

        $image_icon['icon'] = request('icon');

        $data = CmsCategoryResource::toArray($this->language_repo, $this->category_repo, $image_icon);
        $data = $this->category_repo->create($data);

        $data->load([
            'categoryImg.galleryType',
            'categoryIcon.galleryType'
        ]);

        $data = CategoryResource::make($data);
        return ApiResponse::format(201, $data, 'Successful Query');
    }

    public function delete($category_id)
    {
        $this->category_repo->delete($category_id);
        return ApiResponse::format(204, [], 'Deleted Successfully');
    }

    public function update($category_id)
    {
        $image_icon = [];

        if (request('image')){
            $image_icon['image'] = request('image');
        }

        if (request('icon')){
            $image_icon['icon'] = request('icon');
        }

        $data = CmsCategoryResource::toArray($this->language_repo, $this->category_repo, $image_icon, $category_id);

        $data = $this->category_repo->update($category_id, $data);
        $data->load([
            'categoryImg.galleryType',
            'categoryIcon.galleryType'
        ]);
        $data = CategoryResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function removeImages($category_id)
    {
        $image_icon = [];
        if (request()->has('image')) {
            $image_icon['image'] = null;
        }
        if (request()->has('icon')) {
            $image_icon['icon'] = null;
        }

        $data = $this->category_repo->update($category_id, $image_icon);
        $data = CategoryResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function restore($category_id)
    {
        $data = $this->category_repo->restore($category_id);
        $data = CategoryResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Categories', \App::make(CategoryExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
