<?php

namespace Modules\WareHouse\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\PurchaseInvoiceRequest;
use Modules\WareHouse\Services\CMS\PurchaseInvoicesService;

class PurchaseInvoiceController extends Controller
{
    private $purchase_invoice_service;

    public function __construct(PurchaseInvoicesService $purchase_invoice_service)
    {
        $this->purchase_invoice_service = $purchase_invoice_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->purchase_invoice_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param PurchaseInvoiceRequest $request
     * @return JsonResponse|void
     */
    public function store(PurchaseInvoiceRequest $request)
    {
        return $this->purchase_invoice_service->store();
    }

    /**
     * Show the specified resource.
     * @param PurchaseInvoiceRequest $request
     * @return JsonResponse|void
     */
    public function show(PurchaseInvoiceRequest $request)
    {
        return $this->purchase_invoice_service->show($request->purchase_invoice);
    }

    /**
     * Update the specified resource in storage.
     * @param PurchaseInvoiceRequest $request
     * @return JsonResponse|void
     */
    public function update(PurchaseInvoiceRequest $request)
    {
        return $this->purchase_invoice_service->update($request->purchase_invoice);
    }

    /**
     * Remove the specified resource from storage.
     * @param PurchaseInvoiceRequest $request
     * @return JsonResponse
     */
    public function destroy(PurchaseInvoiceRequest $request)
    {
        return $this->purchase_invoice_service->delete($request->purchase_invoice);
    }
}
