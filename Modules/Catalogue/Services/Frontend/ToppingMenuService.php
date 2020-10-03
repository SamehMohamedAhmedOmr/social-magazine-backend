<?php

namespace Modules\Catalogue\Services\Frontend;

use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\Frontend\ToppingMenuRepository;
use Modules\Catalogue\Transformers\Frontend\ToppingMenuResource;
use Modules\Settings\Repositories\LanguageRepository;

class ToppingMenuService extends LaravelServiceClass
{
    private $topping_menu_repo;
    private $language_repo;
    private $cache_key;

    public function __construct(ToppingMenuRepository $topping_menu_repo, LanguageRepository $language_repo)
    {
        $this->topping_menu_repo = $topping_menu_repo;
        $this->language_repo = $language_repo;
        $this->cache_key = 'topping';
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

        $data = $this->topping_menu_repo->paginate($per_page, [], $search, $sort_by, $order_by, $filter_language);
        $pagination = Pagination::preparePagination($data);
        $data = ToppingMenuResource::collection($data->items());

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    public function show($slug)
    {
        $data = $this->topping_menu_repo->get($slug);
        $data = ToppingMenuResource::make($data);

        return ApiResponse::format(200, $data, 'Successful Query');
    }
}
