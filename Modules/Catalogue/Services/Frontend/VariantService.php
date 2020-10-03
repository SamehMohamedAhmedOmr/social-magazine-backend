<?php

namespace Modules\Catalogue\Services\Frontend;

use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\Frontend\VariantRepository;
use Modules\Catalogue\Transformers\Frontend\VariantResource;
use Modules\Settings\Repositories\LanguageRepository;

class VariantService extends LaravelServiceClass
{
    private $variant_repo;
    private $language_repo;
    private $cache_key;
    private $language;

    public function __construct(VariantRepository $variant_repo, LanguageRepository $language_repo)
    {
        $this->variant_repo = $variant_repo;
        $this->language_repo = $language_repo;
        $this->cache_key = 'variant';
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

        $data = $this->variant_repo->paginate($per_page, [], $search, $sort_by, $order_by, $filter_language);
        $pagination = Pagination::preparePagination($data);
        $data = VariantResource::collection($this->variant_repo->loadRelations($data));

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    public function show($slug)
    {
        $data = $this->variant_repo->get($slug);
        $data = VariantResource::make($data);

        return ApiResponse::format(200, $data, 'Successful Query');
    }
}
