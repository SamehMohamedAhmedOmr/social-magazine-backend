<?php

namespace Modules\WareHouse\Http\Controllers\Frontend\Shipment; ## He did it :DD

use App\Http\Controllers\Controller;
use Modules\WareHouse\Services\Frontend\Shipment\CompanyService as ShippingCompanyService;

class CompaniesController extends Controller
{
    private $shipping_company_service;

    public function __construct(ShippingCompanyService $shipping_company_service)
    {
        $this->shipping_company_service = $shipping_company_service;
    }

    public function index()
    {
        return $this->shipping_company_service->index();
    }
}
