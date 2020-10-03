<?php

namespace Modules\WareHouse\Http\Controllers\CMS\Shipment; ## He did it :DD

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\Shipment\CompanyRequest as ShippingCompanyRequest;
use Modules\WareHouse\Services\CMS\Shipment\CompanyService as ShippingCompanyService;

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

    public function store(ShippingCompanyRequest $request)
    {
        return $this->shipping_company_service->store();
    }

    public function show(Request $request)
    {
        return $this->shipping_company_service->show($request->company);
    }

    public function update(ShippingCompanyRequest $request)
    {
        return $this->shipping_company_service->update($request->company);
    }

    public function destroy(Request $request)
    {
        return $this->shipping_company_service->delete($request->company);
    }

    public function restore(Request $request)
    {
        return $this->shipping_company_service->restore($request->company);
    }

    public function export(PaginationRequest $request)
    {
        return $this->shipping_company_service->export();
    }
}
