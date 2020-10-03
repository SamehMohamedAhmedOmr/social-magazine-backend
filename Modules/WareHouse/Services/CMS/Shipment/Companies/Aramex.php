<?php

namespace Modules\WareHouse\Services\CMS\Shipment\Companies;

use Modules\WareHouse\Exceptions\ShipmentException;
use Modules\WareHouse\Repositories\OrderRepository;
use Modules\WareHouse\Transformers\CMS\Shipment\AramexRequestResource;
use SoapClient;
use Modules\Users\Repositories\ClientRepository;
use \Exception;

class Aramex implements ShipmentInterface
{
    protected $shipment_path;

    protected $tracking_path;

    protected $client_repository;

    protected $aramex_request;

    protected $order_repository;

    public function __construct(
        ClientRepository $client_repository,
        AramexRequestResource $aramex_request,
        OrderRepository $order_repository
    )
    {
        $this->shipment_path = config('warehouse.shipments.aramex.shipment_path');
        $this->tracking_path = config('warehouse.shipments.aramex.tracking_path');
        $this->aramex_request = $aramex_request;
        $this->client_repository = $client_repository;
        $this->order_repository = $order_repository;
    }

    public function create($order_id)
    {
        return $this->handleError(function () use ($order_id){
            $data = $this->setShipmentBody($order_id);

            $response = $this->connection($this->shipment_path)->CreateShipments($data);

            throw_if($response->HasErrors == true, new ShipmentException(['aramex' => $response->Notifications]));

            $getShipmentReceipt = $this->getShipmentReceipt($response);

            return [
                'tacking_id' => $response->Shipments->ProcessedShipment->ID,
                'receipt' => $getShipmentReceipt,
                'current_status' => 'Pending',
            ];
        });

    }

    public function getShipmentReceipt($shipment)
    {
        $url = $shipment->Shipments->ProcessedShipment->ShipmentLabel->LabelURL;
        $pdfContent = file_get_contents($url);
        $file_name = 'aramex_' . substr($url, strrpos($shipment->Shipments->ProcessedShipment->ShipmentLabel->LabelURL, '/') + 1);
        $path = storage_path('app/public/shipments-receipts');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $path . '/' . $file_name;
        file_put_contents($file, $pdfContent);
        return '/public/shipments-receipts/'.$file_name;
    }


    public function track($shipment)
    {
        return $this->handleError(function () use ($shipment) {

            $response = $this->connection($this->tracking_path)->TrackShipments($this->setTrackingBody($shipment->tracking_id));

            throw_if(
                $response->HasErrors,
                new ShipmentException(['aramex' => $response->Notifications])
            );

            if (!isset($response->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateDescription)) {
                return null;
            }

            return $response->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateDescription;
        });
    }

    public function connection($connectionParams = null)
    {
        return new SoapClient($connectionParams);
    }

    protected function setShipmentBody($order_id)
    {
        $order_object = $this->order_repository->get($order_id, [], 'id', ['orderItems.product.currentLanguage', 'paymentMethod', 'address.district', 'user.client']);

        throw_if(!isset($order_object->user->client), new ShipmentException([
            'bosta' => ['user has not client entity']
        ]));

        $address_object = $order_object->address;

        $user_object = $order_object->user;

        $client_object = $user_object->client;

        return $this->aramex_request->setShipmentBody($client_object, $user_object, $address_object, $order_object);
    }

    protected function setTrackingBody($shipment_id)
    {
        return $this->aramex_request->setTrackingBody($shipment_id);
    }


    public function handleError($callback)
    {
        try {
            return $callback();
        } catch (Exception $exception) {
            throw new ShipmentException([
                'aramex' => [$exception->getMessage()]
            ]);
        }
    }
}
