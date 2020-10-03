<?php

namespace Modules\WareHouse\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\PaymentEntryRequest;
use Modules\WareHouse\Services\CMS\PaymentEntryService;

class PaymentEntryController extends Controller
{
    private $payment_entry_service;

    public function __construct(PaymentEntryService $payment_entry_service)
    {
        $this->payment_entry_service = $payment_entry_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->payment_entry_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param PaymentEntryRequest $request
     * @return JsonResponse
     */
    public function store(PaymentEntryRequest $request)
    {
        return $this->payment_entry_service->store();
    }

    /**
     * Show the specified resource.
     * @param PaymentEntryRequest $request
     * @return JsonResponse
     */
    public function show(PaymentEntryRequest $request)
    {
        return $this->payment_entry_service->show($request->payment_entry);
    }


    /**
     * Update the specified resource in storage.
     * @param PaymentEntryRequest $request
     * @return JsonResponse
     */
    public function update(PaymentEntryRequest $request)
    {
        return $this->payment_entry_service->update($request->payment_entry);
    }

    /**
     * Remove the specified resource from storage.
     * @param PaymentEntryRequest $request
     * @return JsonResponse
     */
    public function destroy(PaymentEntryRequest $request)
    {
        return $this->payment_entry_service->delete($request->payment_entry);
    }

    /**
     * Get PDF.
     * @param PaymentEntryRequest $request
     * @return JsonResponse|void
     */
    public function generatePDf(PaymentEntryRequest $request)
    {
        return $this->payment_entry_service->getWithPDF($request->payment_entry);
    }
}
