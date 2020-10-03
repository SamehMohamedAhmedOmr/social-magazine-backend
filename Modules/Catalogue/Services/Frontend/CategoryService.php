<?php

namespace Modules\Catalogue\Services\Frontend;

use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\Frontend\CategoryRepository;
use Modules\Settings\Repositories\LanguageRepository;
use Modules\Catalogue\Transformers\Frontend\CategoryResource;

class CategoryService extends LaravelServiceClass
{
    private $category_repo;
    private $language_repo;
    private $cache_key;
    private $language;

    public function __construct(CategoryRepository $category_repo, LanguageRepository $language_repo)
    {
        $this->category_repo = $category_repo;
        $this->language_repo = $language_repo;
        $this->cache_key = 'category';
    }

    public function index()
    {
        $search['search_key'] = request('search_key');
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['name', 'created_at'])
            ? request('sort_by') : 'id';
        $filter_language = Session::get('language_id');

        $per_page = request()->per_page ?: 15;

        $data = $this->category_repo->paginate($per_page, [], $search, $sort_by, $order_by, $filter_language);
        $pagination = Pagination::preparePagination($data);
        $data = CategoryResource::collection($this->category_repo->loadRelations($data));

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    public function show($slug)
    {
        $data = $this->category_repo->get($slug);
        $data = CategoryResource::make($data);

        return ApiResponse::format(200, $data, 'Successful Query');
    }
}
