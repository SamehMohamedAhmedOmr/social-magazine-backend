<?php

namespace Modules\Catalogue\Services\Frontend;

use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\Repositories\Frontend\BrandRepository;
use Modules\Catalogue\Transformers\Frontend\BrandResource;
use Modules\Settings\Repositories\LanguageRepository;

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

    public function index()
    {
        $search['search_key'] = request('search_key');
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['name', 'created_at'])
            ? request('sort_by') : 'id';
        $filer_language = Session::get('language_id');

        $per_page = request()->per_page ?: 15;

        $data = $this->brand_repo->paginate($per_page, [], $search, $sort_by, $order_by, $filer_language);
        $pagination = Pagination::preparePagination($data);
        $data = BrandResource::collection($data->items());

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    public function show($slug)
    {
        $data = $this->brand_repo->get($slug);

        $data->load([
            'brandImg.galleryType'
        ]);

        $data = BrandResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }
}
