<?php

namespace Modules\WareHouse\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\WareHouse\Services\Frontend\Payments\PaymentsCallbackService;

class PaymentsCallbackControllers extends Controller
{
    protected $paymentsCallbackService;

    public function __construct(PaymentsCallbackService $paymentsCallbackService)
    {
        $this->paymentsCallbackService = $paymentsCallbackService;
    }

    public function weAcceptProcessed(Request $request)
    {
        return $this->paymentsCallbackService->weAcceptProcessed($request);
    }

    public function weAcceptResponse(Request $request)
    {
        return $this->paymentsCallbackService->weAcceptResponse($request);
    }
}
