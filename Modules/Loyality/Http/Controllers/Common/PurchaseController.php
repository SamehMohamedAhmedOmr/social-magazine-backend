<?php

namespace Modules\Loyality\Http\Controllers\Common;

use Illuminate\Routing\Controller;
use Modules\Loyality\Http\Requests\Common\CalculatePointsRequest;
use Modules\Loyality\Http\Requests\Common\SavePointsRequest;
use Modules\Loyality\Services\Common\PurchaseService;

class PurchaseController extends Controller
{
    private $purchase_service;

    public function __construct(PurchaseService $purchase_service)
    {
        $this->purchase_service = $purchase_service;
    }

    public function calculatePoints(CalculatePointsRequest $request)
    {
        return $this->purchase_service->calculate($request);
    }

    public function purchasePoints(SavePointsRequest $request)
    {
        return $this->purchase_service->save($request);
    }
}
