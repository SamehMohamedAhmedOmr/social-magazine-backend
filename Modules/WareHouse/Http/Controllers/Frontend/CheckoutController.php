<?php

namespace Modules\WareHouse\Http\Controllers\Frontend;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\Frontend\CheckoutRequest;
use Modules\WareHouse\Http\Requests\Frontend\OrderRequest;
use Modules\WareHouse\Services\Frontend\CheckoutOrderService;
use Modules\WareHouse\Services\Frontend\OrderService;
use Throwable;

class CheckoutController extends Controller
{
    private $checkoutOrderService;
    private $orderService;

    public function __construct(
        CheckoutOrderService $checkoutOrderService,
        OrderService $orderService
    )
    {
        $this->checkoutOrderService = $checkoutOrderService;
        $this->orderService = $orderService;
    }

    /**
     * Checkout.
     * @param CheckoutRequest $request
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function checkout(CheckoutRequest $request)
    {
        return $this->checkoutOrderService->checkout($request);
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->orderService->index(\Auth::id());
    }

    /**
     * Display a listing of the resource.
     * @param OrderRequest $request
     * @return JsonResponse
     */
    public function get(OrderRequest $request)
    {
        return $this->orderService->show($request->order);
    }
}
