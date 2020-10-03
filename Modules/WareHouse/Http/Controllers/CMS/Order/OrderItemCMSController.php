<?php

namespace Modules\WareHouse\Http\Controllers\CMS\Order;

use Illuminate\Routing\Controller;
use Modules\WareHouse\Http\Requests\CMS\Order\CMSEditOrderRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\CMSOrderItemRequest;
use Modules\WareHouse\Http\Requests\CMS\Order\CMSOrderRequest;
use Modules\WareHouse\Http\Requests\Frontend\OrderRequest;

use Modules\WareHouse\Services\CMS\Order\OrderItemCMSService;
use Throwable;

class OrderItemCMSController extends Controller
{
    private $orderItemCMSService;



    public function __construct(OrderItemCMSService $orderItemCMSService)
    {
        $this->orderItemCMSService = $orderItemCMSService;
    }

    /**
     * Checkout.
     * @param CMSOrderItemRequest $request
     * @return void
     */
    public function edit(CMSOrderItemRequest $request)
    {
        return $this->orderItemCMSService->edit($request);
    }


    /**
     * Display a listing of the resource.
     * @param CMSOrderItemRequest $request
     * @return void
     */
    public function delete(CMSOrderItemRequest $request)
    {
        return $this->orderItemCMSService->delete($request->order_item_id);
    }
}
