<?php

namespace Modules\WareHouse\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\PurchaseOrderEmailRequest;
use Modules\WareHouse\Http\Requests\PurchaseOrderRequest;
use Modules\WareHouse\Services\CMS\PurchaseOrderService;

class PurchaseOrderController extends Controller
{
    private $purchase_order_service;

    public function __construct(PurchaseOrderService $purchase_order_service)
    {
        $this->purchase_order_service = $purchase_order_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->purchase_order_service->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param PurchaseOrderRequest $request
     * @return JsonResponse|void
     */
    public function store(PurchaseOrderRequest $request)
    {
        return $this->purchase_order_service->store();
    }

    /**
     * Show the specified resource.
     * @param PurchaseOrderRequest $request
     * @return JsonResponse|void
     */
    public function show(PurchaseOrderRequest $request)
    {
        return $this->purchase_order_service->show($request->purchase_order);
    }

    /**
     * Update the specified resource in storage.
     * @param PurchaseOrderRequest $request
     * @return JsonResponse|void
     */
    public function update(PurchaseOrderRequest $request)
    {
        return $this->purchase_order_service->update($request->purchase_order);
    }

    /**
     * Remove the specified resource from storage.
     * @param PurchaseOrderRequest $request
     * @return JsonResponse|void
     */
    public function destroy(PurchaseOrderRequest $request)
    {
        return $this->purchase_order_service->delete($request->purchase_order);
    }

    /**
     * Send Emails with PDF
     * @param PurchaseOrderEmailRequest $request
     * @return JsonResponse|void
     */
    public function sendEmail(PurchaseOrderEmailRequest $request)
    {
        return $this->purchase_order_service->sendEmail($request->purchase_order);
    }

    /**
     * Get PDF.
     * @param PurchaseOrderRequest $request
     * @return JsonResponse|void
     */
    public function getWithPdf(PurchaseOrderRequest $request)
    {
        return $this->purchase_order_service->getWithPDF($request->purchase_order);
    }
}
