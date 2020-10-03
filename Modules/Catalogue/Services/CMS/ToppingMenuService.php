<?php

namespace Modules\Catalogue\Services\CMS;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\ExcelExports\ToppingMenuExport;
use Modules\Catalogue\Repositories\CMS\ToppingMenuRepository;
use Modules\Catalogue\Transformers\CMS\ToppingMenu\ToppingMenuResource;
use Modules\Catalogue\Transformers\Repo\Facades\ToppingMenuResource as ToppingRepoResource;
use Modules\Settings\Repositories\LanguageRepository;

class ToppingMenuService extends LaravelServiceClass
{
    private $topping_repo;
    private $language_repo;
    private $cache_key;

    public function __construct(ToppingMenuRepository $topping_repo, LanguageRepository $language_repo)
    {
        $this->topping_repo = $topping_repo;
        $this->language_repo = $language_repo;
        $this->cache_key = 'topping';
    }

    public function all()
    {
        $filters = $this->filters();
        $data = $this->topping_repo->index($filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $data =  ToppingMenuResource::collection($this->topping_repo->loadRelations($data,[
            'currentLanguage',
            'products.currentLanguage',
        ]));
        return ApiResponse::format(200, $data);

    }

    public function index()
    {
        $filters = $this->filters();
        $data = $this->topping_repo->paginate($filters['per_page'], [], $filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $pagination = Pagination::preparePagination($data);
        $data = ToppingMenuResource::collection($this->topping_repo->loadRelations($data));

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    protected function filters()
    {
        $search['search_key'] = request('search_key');
        $per_page = request()->per_page ?: 15;

        ## Booleans
        $search['is_active'] = request()->has('is_active')
            ? filter_var(request('is_active'), FILTER_VALIDATE_BOOLEAN) : null;
        $search['trashed'] = request()->has('trashed')
            ? filter_var(request('trashed'), FILTER_VALIDATE_BOOLEAN) : null;

        ## Order
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['name', 'created_at'])
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

    public function show($topping_id)
    {
        $data = $this->topping_repo->get($topping_id);
        $data = ToppingMenuResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function store()
    {
        $data = ToppingRepoResource::toArray(request());
        $data = $this->topping_repo->create($data);
        $data = ToppingMenuResource::make($data);
        return ApiResponse::format(201, $data, 'Successful Query');
    }

    public function delete($topping_id)
    {
        $this->topping_repo->delete($topping_id);
        return ApiResponse::format(204, [], 'Deleted Successfully');
    }

    public function update($topping_id)
    {
        $data = ToppingRepoResource::toArray(request());
        $data = $this->topping_repo->update($topping_id, $data);
        $data = ToppingMenuResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function restore($topping_id)
    {
        $data = $this->topping_repo->restore($topping_id);
        $data = ToppingMenuResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Topping-Menu', \App::make(ToppingMenuExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
