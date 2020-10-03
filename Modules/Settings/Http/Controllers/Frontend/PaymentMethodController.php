<?php

namespace Modules\Settings\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Services\Frontend\PaymentMethodService;
use Modules\Settings\Services\Frontend\TimeSectionService;

class PaymentMethodController extends Controller
{
    private $paymentMethodService;
    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return void
     */
    public function index(PaginationRequest $request)
    {
        return $this->paymentMethodService->index();
    }
}
