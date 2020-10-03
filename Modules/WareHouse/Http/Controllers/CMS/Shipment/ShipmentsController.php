<?php

namespace Modules\WareHouse\Http\Controllers\CMS\Shipment; ## He did it :DD

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\WareHouse\Http\Requests\Shipment\ShipmentRequest;
use Modules\WareHouse\Http\Requests\Shipment\WebHookRequest;
use Modules\WareHouse\Services\CMS\Shipment\ShipmentService;

class ShipmentsController extends Controller
{
    private $shipment_service;

    public function __construct(ShipmentService $shipment_service)
    {
        $this->shipment_service = $shipment_service;
    }

    public function store(ShipmentRequest $request)
    {
        return $this->shipment_service->store();
    }

    public function show(Request $request)
    {
        return $this->shipment_service->get($request->shipment);
    }

    public function shipmentReceipt(Request $request)
    {
        return $this->shipment_service->shipmentReceipt($request->shipment);
    }

    public function track(Request $request)
    {
        return $this->shipment_service->track($request->shipment);
    }

    public function webHook(WebHookRequest $request)
    {
        return $this->shipment_service->webHook($request);
    }
}
