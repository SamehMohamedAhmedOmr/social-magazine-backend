<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\PaymentMethodsRequest;
use Modules\Settings\Services\CMS\PaymentMethodService;

class PaymentMethodsController extends Controller
{
    private $payment_method_service;

    public function __construct(PaymentMethodService $payment_method_service)
    {
        $this->payment_method_service = $payment_method_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->payment_method_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param PaymentMethodsRequest $request
     * @return JsonResponse|void
     */
    public function store(PaymentMethodsRequest $request)
    {
        return $this->payment_method_service->store();
    }

    /**
     * Show the specified resource.
     * @param PaymentMethodsRequest $request
     * @return JsonResponse|void
     */
    public function show(PaymentMethodsRequest $request)
    {
        return $this->payment_method_service->show($request->payment_method);
    }

    /**
     * Update the specified resource in storage.
     * @param PaymentMethodsRequest $request
     * @return JsonResponse|void
     */
    public function update(PaymentMethodsRequest $request)
    {
        return $this->payment_method_service->update($request->payment_method);
    }

    /**
     * Remove the specified resource from storage.
     * @param PaymentMethodsRequest $request
     * @return JsonResponse|void
     */
    public function destroy(PaymentMethodsRequest $request)
    {
        return $this->payment_method_service->delete($request->payment_method);
    }

    public function export(PaginationRequest $request)
    {
        return $this->payment_method_service->export();
    }
}
