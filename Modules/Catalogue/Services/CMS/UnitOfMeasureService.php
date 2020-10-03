<?php

namespace Modules\Catalogue\Services\CMS;

use Illuminate\Support\Facades\Session;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Catalogue\ExcelExports\UnitOfMeasureExport;
use Modules\Catalogue\Repositories\CMS\UnitOfMeasureRepository;
use Modules\Catalogue\Transformers\CMS\TryResource\UnitOfMeasureResourceTry;
use Modules\Catalogue\Transformers\CMS\UnitOfMeasure\UnitOfMeasureResource;
use Modules\Settings\Repositories\LanguageRepository;
use Modules\Catalogue\Transformers\Repo\UnitOfMeasureResource as CmsUnitOfMeasureResourceResource;

class UnitOfMeasureService extends LaravelServiceClass
{
    private $unit_of_measure_repo;
    private $language_repo;

    public function __construct(UnitOfMeasureRepository $unit_of_measure_repo, LanguageRepository $language_repo)
    {
        $this->unit_of_measure_repo = $unit_of_measure_repo;
        $this->language_repo = $language_repo;
    }

    public function all()
    {
        $filters = $this->filters();
        $data =  $this->unit_of_measure_repo->index($filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $data = UnitOfMeasureResource::collection($this->unit_of_measure_repo->loadRelations($data,[
            'currentLanguage'
        ]));
        return ApiResponse::format(200, $data);
    }

    public function index()
    {
        $filters = $this->filters();
        $data =  $this->unit_of_measure_repo->paginate($filters['per_page'], [], $filters['search'], $filters['sort_by'],
            $filters['order_by'], $filters['sort_language']);
        $pagination = Pagination::preparePagination($data);
        $data = UnitOfMeasureResource::collection($this->unit_of_measure_repo->loadRelations($data));
        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    protected function filters()
    {
        $search['search_key'] = request('search_key') ;
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

    public function show($id)
    {
        $data = $this->unit_of_measure_repo->get($id);
        $data = UnitOfMeasureResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function store()
    {
        $data = CmsUnitOfMeasureResourceResource::toArray($this->language_repo, $this->unit_of_measure_repo);
        $data = $this->unit_of_measure_repo->create($data);
        $data = UnitOfMeasureResource::make($data);
        return ApiResponse::format(201, $data, 'Successful Query');
    }


    public function update($id)
    {
        $data = CmsUnitOfMeasureResourceResource::toArray($this->language_repo, $this->unit_of_measure_repo);
        $data = $this->unit_of_measure_repo->update($id, $data);
        $data = UnitOfMeasureResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function delete($id)
    {
        $this->unit_of_measure_repo->delete($id);
        return ApiResponse::format(204, [], 'Deleted Successfully');
    }

    public function restore($id)
    {
        $data = $this->unit_of_measure_repo->restore($id);
        $data = UnitOfMeasureResource::make($data);
        return ApiResponse::format(200, $data, 'Updated Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Unit-of-measure', \App::make(UnitOfMeasureExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
