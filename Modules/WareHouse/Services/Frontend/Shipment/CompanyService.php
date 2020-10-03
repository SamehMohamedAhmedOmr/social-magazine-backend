<?php

namespace Modules\WareHouse\Services\Frontend\Shipment;

use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\Repositories\CMS\Shipment\CompanyRepository as ShipmentCompanyRepository;
use Modules\WareHouse\Transformers\CMS\Shipment\CompanyResource as ShipmentCompanyResource;

class CompanyService extends LaravelServiceClass
{
    private $shipment_company_repo;

    public function __construct(ShipmentCompanyRepository $shipment_company_repo)
    {
        $this->shipment_company_repo = $shipment_company_repo;
    }

    public function index()
    {
        $search['search_key'] = request('search_key');
        $search['is_active'] = true;

        $per_page = request()->per_page ?: 15;

        $data =  $this->shipment_company_repo->paginate(null, $per_page, $search);
        $pagination = Pagination::preparePagination($data);

        return ApiResponse::format(200, ShipmentCompanyResource::collection($data), 'Successful Query', $pagination);
    }
}
