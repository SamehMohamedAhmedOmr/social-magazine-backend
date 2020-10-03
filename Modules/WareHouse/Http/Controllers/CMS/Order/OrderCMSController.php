<?php

namespace Modules\WareHouse\Http\Controllers\CMS\Order;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\CMSChangeOrderStatusRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\CMSEditOrderRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\CMSOrderRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\OrderExportRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\OrderListFilteringRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\OrderUserIDRequest;
use Modules\WareHouse\Http\Requests\Frontend\OrderRequest;
use Modules\WareHouse\Services\CMS\Order\OrderCMSService;
use Modules\WareHouse\Services\CMS\Order\OrderEditCMSService;
use Modules\WareHouse\Services\CMS\Order\OrderService;
use Throwable;

class OrderCMSController extends Controller
{
    private $orderCMSService;
    private $orderService;
    private $orderEditCMSService;


    public function __construct(
        OrderCMSService $orderCMSService,
        OrderEditCMSService $orderEditCMSService,
        OrderService $orderService
    )
    {
        $this->orderCMSService = $orderCMSService;
        $this->orderService = $orderService;
        $this->orderEditCMSService = $orderEditCMSService;
    }

    /**
     * Checkout.
     * @param CMSOrderRequest $request
     * @return void
     * @throws Throwable
     */
    public function checkout(CMSOrderRequest $request)
    {
        return $this->orderCMSService->checkout($request);
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @param OrderListFilteringRequest $orderListFilteringRequest
     * @param OrderUserIDRequest $orderUserIDRequest
     * @return void
     */
    public function index(PaginationRequest $request,
                          OrderListFilteringRequest $orderListFilteringRequest,
                          OrderUserIDRequest $orderUserIDRequest)
    {
        return $this->orderService->index();
    }

    /**
     * Display a listing of the resource.
     * @param OrderRequest $request
     * @return void
     */
    public function get(OrderRequest $request)
    {
        return $this->orderService->show($request->order);
    }

    /**
     * Display a listing of the resource.
     * @param CMSEditOrderRequest $request
     * @return void
     */
    public function edit(CMSEditOrderRequest $request)
    {
        return $this->orderEditCMSService->editOrder($request);
    }

    /**
     * Display a listing of the resource.
     * @param CMSChangeOrderStatusRequest $request
     * @return JsonResponse
     */
    public function editStatus(CMSChangeOrderStatusRequest $request)
    {
        return $this->orderEditCMSService->editStatus($request);
    }

    public function exportOrders(OrderListFilteringRequest $orderListFilteringRequest,
                                 OrderExportRequest $exportRequest)
    {
        return $this->orderService->exportOrders();
    }

}
