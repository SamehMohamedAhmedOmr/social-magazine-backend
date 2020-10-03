<?php

namespace Modules\Loyality\Http\Controllers\Common;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Loyality\Services\Common\ReturnOrderService;

class ReturnOrdersController extends Controller
{
    private $return_order_service;

    public function __construct(ReturnOrderService $return_order_service)
    {
        $this->return_order_service = $return_order_service;
    }

    public function returnOrder(Request $request)
    {
        return $this->return_order_service->returnOrder($request);
    }
}
