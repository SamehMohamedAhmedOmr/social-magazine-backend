<?php

namespace Modules\Catalogue\Services\CMS;

use Illuminate\Support\Facades\Cache;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\ExcelExports\VariantValuesExport;
use Modules\Catalogue\Repositories\CMS\VariantValueRepository;
use Modules\Catalogue\Transformers\CMS\VariantValue\VariantValueResource;
use Modules\Catalogue\Transformers\Repo\Facades\VariantValueResource as VariantValueRepoResource;

class VariantValueService extends LaravelServiceClass
{
    private $variant_value_repo;
    private $cache_key;

    public function __construct(VariantValueRepository $variant_value_repo)
    {
        $this->variant_value_repo = $variant_value_repo;
        $this->cache_key = 'variation';
    }

    public function all()
    {
        $filters = $this->filters();
        $data = $this->variant_value_repo->index($filters['search'],
            $filters['sort_by'], $filters['order_by']);

        $data->load([
            'currentLanguage',
            'variant.currentLanguage'
        ]);

        $data =  VariantValueResource::collection($data);
        return ApiResponse::format(200, $data);

    }

    public function index()
    {
        $filters = $this->filters();
        $data = $this->variant_value_repo->paginate($filters['per_page'], [], $filters['search'],
            $filters['sort_by'], $filters['order_by']);
        $pagination = Pagination::preparePagination($data);
        $data = VariantValueResource::collection($data);

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    protected function filters()
    {
        $search['search_key'] = request('search_key');
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['value', 'created_at'])
            ? request('sort_by') : 'id';

        $search['trashed'] = request()->has('trashed')
            ? filter_var(request('trashed'), FILTER_VALIDATE_BOOLEAN) : null;

        $search['is_active'] = request()->has('is_active')
            ? filter_var(request('is_active'), FILTER_VALIDATE_BOOLEAN) : null;

        $search['variant_id'] = request()->has('variant_id') ?
            request('variant_id') : null;

        $per_page = request()->per_page ?: 15;

        return [
            'search' => $search,
            'order_by' => $order_by,
            'sort_by' => $sort_by,
            'per_page' => $per_page,
        ];
    }

    public function show($variant_id)
    {
        $data = $this->variant_value_repo->get($variant_id);
        $data = VariantValueResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function store()
    {
        $data = VariantValueRepoResource::toArray(request());
        $data = $this->variant_value_repo->create($data);
        $data = VariantValueResource::make($data);
        return ApiResponse::format(201, $data, 'Successful Query');
    }

    public function delete($variant_id)
    {
        $this->variant_value_repo->delete($variant_id);
        return ApiResponse::format(204, [], 'Deleted Successfully');
    }

    public function update($variant_id)
    {
        $data = VariantValueRepoResource::toArray(request());
        $data = $this->variant_value_repo->update($variant_id, $data);
        $data = VariantValueResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function restore($variant_id)
    {
        $data = $this->variant_value_repo->restore($variant_id);
        $data = VariantValueResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Variants-values', \App::make(VariantValuesExport::class));

        return ApiResponse::format(200, $file_path);
    }

}
