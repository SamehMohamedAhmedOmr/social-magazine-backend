<?php

namespace Modules\Catalogue\Services\CMS;

use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\ExcelExports\VariantExport;
use Modules\Catalogue\Repositories\CMS\VariantRepository;
use Modules\Catalogue\Transformers\CMS\Variant\VariantResource;
use Modules\Catalogue\Transformers\Repo\Facades\VariantResource as VariantRepoResource;
use Modules\Settings\Repositories\LanguageRepository;

class VariantService extends LaravelServiceClass
{
    private $variant_repo;
    private $language_repo;
    private $cache_key;

    public function __construct(VariantRepository $variant_repo, LanguageRepository $language_repo)
    {
        $this->variant_repo = $variant_repo;
        $this->language_repo = $language_repo;
        $this->cache_key = 'variant';
    }

    public function all()
    {
        $filters = $this->filters();
        $data = $this->variant_repo->index($filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $data = VariantResource::collection($this->variant_repo->loadRelations($data,[
            'currentLanguage',
            'values.currentLanguage'
        ]));
        return ApiResponse::format(200, $data);
    }


    public function index()
    {
        $filters = $this->filters();
        $data =  $this->variant_repo->paginate($filters['per_page'], [], $filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $pagination = Pagination::preparePagination($data);
        $data = VariantResource::collection($this->variant_repo->loadRelations($data));

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    protected function filters()
    {
        $search['search_key'] = request('search_key') ;
        $per_page = request()->per_page ?: 15;

        ## Booleans
        $search['is_active'] = request()->has('is_active')
            ? filter_var(request('is_active'), FILTER_VALIDATE_BOOLEAN) : null;
        $search['is_color'] = request()->has('is_color')
            ? filter_var(request('is_color'), FILTER_VALIDATE_BOOLEAN) : null;
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

    public function show($variant_id)
    {
        $data = $this->variant_repo->get($variant_id);
        $data = VariantResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function store()
    {
        $data = VariantRepoResource::toArray(request());
        $data = $this->variant_repo->create($data);
        $data = VariantResource::make($data);

        return ApiResponse::format(201, $data, 'Successful Query');
    }

    public function delete($variant_id)
    {
        $this->variant_repo->delete($variant_id);
        return ApiResponse::format(204, [], 'Deleted Successfully');
    }

    public function update($variant_id)
    {
        $data = VariantRepoResource::toArray(request());
        $data = $this->variant_repo->update($variant_id, $data);
        $data = VariantResource::make($data);

        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function restore($variant_id)
    {
        $data = $this->variant_repo->restore($variant_id);
        $data = VariantResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Variants', \App::make(VariantExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
