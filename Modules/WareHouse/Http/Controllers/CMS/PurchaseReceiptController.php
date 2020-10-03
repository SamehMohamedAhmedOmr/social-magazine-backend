<?php

namespace Modules\WareHouse\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\PurchaseReceiptRequest;
use Modules\WareHouse\Http\Requests\PurchaseReceiptStatusRequest;
use Modules\WareHouse\Services\CMS\PurchaseReceiptService;

class PurchaseReceiptController extends Controller
{
    private $purchase_receipt_service;

    public function __construct(PurchaseReceiptService $purchase_receipt_service)
    {
        $this->purchase_receipt_service = $purchase_receipt_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->purchase_receipt_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param PurchaseReceiptRequest $request
     * @return JsonResponse|void
     * @throws ValidationException
     */
    public function store(PurchaseReceiptRequest $request)
    {
        return $this->purchase_receipt_service->store();
    }

    /**
     * Show the specified resource.
     * @param PurchaseReceiptRequest $request
     * @return JsonResponse|void
     */
    public function show(PurchaseReceiptRequest $request)
    {
        return $this->purchase_receipt_service->show($request->purchase_receipt);
    }

    /**
     * Update the specified resource in storage.
     * @param PurchaseReceiptRequest $request
     * @return JsonResponse|void
     * @throws ValidationException
     */
    public function update(PurchaseReceiptRequest $request)
    {
        return $this->purchase_receipt_service->update($request->purchase_receipt);
    }

    /**
     * Remove the specified resource from storage.
     * @param PurchaseReceiptRequest $request
     * @return JsonResponse|void
     */
    public function destroy(PurchaseReceiptRequest $request)
    {
        return $this->purchase_receipt_service->delete($request->purchase_receipt);
    }

    /**
     * Remove the specified resource from storage.
     * @param PurchaseReceiptStatusRequest $request
     * @return JsonResponse|void
     */
    public function changeStatus(PurchaseReceiptStatusRequest $request)
    {
        return $this->purchase_receipt_service->changeStatus($request->purchase_receipt);
    }

    /**
     * Show the specified resource.
     * @param PurchaseReceiptRequest $request
     * @return JsonResponse|void
     */
    public function generatePDf(PurchaseReceiptRequest $request)
    {
        return $this->purchase_receipt_service->getWithPDF($request->purchase_receipt);
    }
}
