<?php

namespace Modules\WareHouse\Services\CMS\Shipment;

use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\ExcelExports\ShipmentCompanyExport;
use Modules\WareHouse\Repositories\CMS\Shipment\CompanyRepository as ShipmentCompanyRepository;
use Modules\WareHouse\Transformers\CMS\Shipment\CompanyResource as ShipmentCompanyResource;

class CompanyService extends LaravelServiceClass
{
    private $shipment_company_repo;

    public function __construct(ShipmentCompanyRepository $shipment_company_repo)
    {
        $this->shipment_company_repo = $shipment_company_repo;
    }

    public function all()
    {
        $filters = $this->filters();
        $data = $this->shipment_company_repo->index($filters['search']);

        $data = ShipmentCompanyResource::collection($data);
        return ApiResponse::format(200, $data);
    }

    public function index()
    {
        $filters = $this->filters();
        $data =  $this->shipment_company_repo->paginate(null, $filters['per_page'], $filters['search']);
        $pagination = Pagination::preparePagination($data);
        return ApiResponse::format(200, ShipmentCompanyResource::collection($data), 'Successful Query', $pagination);
    }

    public function filters()
    {
        $search['search_key'] = request('search_key');
        $search['trashed'] = request()->has('trashed')
            ? filter_var(request('is_active'), FILTER_VALIDATE_BOOLEAN) : null;
        $search['is_active'] = request()->has('is_active')
            ? filter_var(request('is_active'), FILTER_VALIDATE_BOOLEAN) : null;

        $per_page = request()->per_page ?: 15;

        return [
            'search' => $search,
            'per_page' => $per_page,
        ];
    }

    public function show($company_id)
    {
        $data = $this->shipment_company_repo->get($company_id);
        return ApiResponse::format(200, ShipmentCompanyResource::make($data), 'Successful Query');
    }

    public function store()
    {
        $data = $this->shipment_company_repo->create(request()->all());
        return ApiResponse::format(201, ShipmentCompanyResource::make($data), 'Successful Query');
    }

    public function delete($company_id)
    {
        $this->shipment_company_repo->delete($company_id);
        return ApiResponse::format(204, [], 'Deleted Successfully');
    }

    public function update($company_id)
    {
        $data = $this->shipment_company_repo->update($company_id, request()->all());
        return ApiResponse::format(200, ShipmentCompanyResource::make($data), 'Updated Successfully');
    }

    public function restore($company_id)
    {
        $data = $this->shipment_company_repo->update($company_id, ['deleted_at' => null]);
        return ApiResponse::format(200, ShipmentCompanyResource::make($data), 'Updated Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Shipment-Companies', \App::make(ShipmentCompanyExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
