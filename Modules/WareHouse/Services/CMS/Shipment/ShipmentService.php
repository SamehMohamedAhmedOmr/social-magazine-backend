<?php

namespace Modules\WareHouse\Services\CMS\Shipment;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\Repositories\CMS\Shipment\CompanyRepository as ShippingCompany;
use Modules\WareHouse\Repositories\CMS\Shipment\ShipmentRepository;
use Modules\WareHouse\Services\CMS\Shipment\Companies\Shipment;
use Modules\WareHouse\Transformers\CMS\Shipment\ShipmentResource;

class ShipmentService extends LaravelServiceClass
{
    const STATUS = [
        10 => 'Pending', 15 => 'In progress', 16 => 'Delivery on route', 20 => 'Picking up', 21 => 'Picking up from warehouse',
        22 => 'Arrived at warehouse', 23 => 'Received at warehouse', 25 => 'Arrived at business', 26 => 'Receiving', 30 => 'Picked up',
        35 => 'Delivering', 36 => 'En route to warehouse', 40 => 'Arrived at customer', 45 => 'Delivered', 50 => 'Canceled',
        55 => 'Failed', 80 => 'Pickup Failed'
    ];

    private $shipment;
    private $shipping_company;
    private $shipment_repository;

    public function __construct(Shipment $shipment, ShippingCompany $shipping_company, ShipmentRepository $shipment_repository)
    {
        $this->shipment = $shipment;
        $this->shipping_company = $shipping_company;
        $this->shipment_repository = $shipment_repository;
    }

    public function store()
    {
        $company = $this->shipping_company->get(request()->company, [], 'key');
        $this->shipment->setShipmentCompany(strtolower($company->key));
        $response = $this->shipment->send(request()->order_id);
        $response['shipping_company_id'] = $company->id;
        $response['order_id'] = request()->order_id;

        $shipment = $this->shipment_repository->create($response);

        $response = ShipmentResource::make($shipment->load('shippingCompany'));

        return ApiResponse::format(201, $response, "Successfully Created");
    }

    public function get($shipment_id)
    {
        $data = $this->shipment_repository->get($shipment_id, [], 'id', 'shippingCompany');
        $data = ShipmentResource::make($data->load('shippingCompany', 'order'));
        return ApiResponse::format(200, $data, "Successful");
    }

    public function track($shipment_id)
    {
        $data = [];
        $shipment = $this->shipment_repository->get($shipment_id, [], 'id', 'shippingCompany');

        $this->shipment->setShipmentCompany($shipment->shippingCompany->key);

        $tracking_result = $this->shipment->track($shipment);

        if (is_string($tracking_result)) {
            $shipment = $this->shipment_repository->updateShipmentModel($shipment, ['current_status' => $tracking_result]);
        }

        $data['shipment'] = ShipmentResource::make($shipment->load('shippingCompany', 'order'));
        $data['tracking']['data'] = is_array($tracking_result) ? $tracking_result : [];
        $data['tracking']['message'] = is_array($tracking_result) ? 'Successful' : 'No tracking history';

        return ApiResponse::format(200, $data, "Successful");
    }

    public function webHook($request)
    {
        $data = $this->shipment_repository->updateShipment(['current_status' => self::STATUS[$request->state->code]], $request->_id, 'tracking_id');
        $data = ShipmentResource::make($data->load('shippingCompany', 'order'));
        return ApiResponse::format(200, $data, "Successful");
    }
}
