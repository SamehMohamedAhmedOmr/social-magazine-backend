<?php

namespace Modules\WareHouse\Services\CMS\Shipment\Companies;

interface ShipmentInterface
{
    public function create($order_id);
    public function connection($connectionParams = null);
    public function getShipmentReceipt($shipment);
    public function track($shipment_id);
    public function handleError($callback);
}
