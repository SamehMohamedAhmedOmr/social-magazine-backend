<?php

namespace Modules\WareHouse\Services\CMS\Shipment\Companies;

use Modules\WareHouse\Exceptions\ShipmentException;
use \Exception;
use GuzzleHttp\Client;
use Modules\Users\Repositories\ClientRepository;
use Modules\WareHouse\Repositories\OrderRepository;
use Modules\WareHouse\Transformers\CMS\Shipment\BostaRequestResource;

class Bosta implements ShipmentInterface
{
    protected $api_uri;

    protected $api_key;

    protected $client_repository;

    protected $bosta_request;

    protected $order_repository;

    public function __construct(
        ClientRepository $client_repository,
        BostaRequestResource $bosta_request,
        OrderRepository $order_repository
    )
    {
        $this->api_uri = config('warehouse.shipments.bosta.api_uri');
        $this->api_key = config('warehouse.shipments.bosta.api_key');
        $this->bosta_request = $bosta_request;
        $this->client_repository = $client_repository;
        $this->order_repository = $order_repository;
    }

    public function create($order_id)
    {
        $data = $this->setBody($order_id);

        return $this->handleError(function () use ($data){

            $response = $this->connection()->post(
                'deliveries',
                [
                    'body' => json_encode($data),
                ]
            );

            $body = json_decode($response->getBody()->getContents());

            $receipt = $this->getShipmentReceipt($body->_id);

            throw_if($response->getStatusCode() != 201, new ShipmentException(['bosta' => $body]));

            return [
                'tacking_id' => $body->_id,
                'tracking_number' => $body->trackingNumber,
                'current_status' => $body->state ? ucfirst($body->state->value) : null,
                'receipt' => $receipt,
            ];
        });
    }

    public function getShipmentReceipt($shipment_id)
    {
        return $this->handleError(function () use ($shipment_id) {
            $response = $this->connection()->get("deliveries/awb/$shipment_id");

            $body = json_decode($response->getBody()->getContents());

            $bin_pdf = base64_decode($body->data, true);
            $file_name = 'bosta_receipt_'.rand(1, 1000000).'.pdf';
            $path = storage_path('app/public/shipments-receipts');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file = $path . '/'.$file_name;
            file_put_contents($file, $bin_pdf);

            return '/public/shipments-receipts/'.$file_name;
        });
    }

    public function track($shipment)
    {
        return $this->handleError(function () use ($shipment) {

            $response = $this->connection()->get("/deliveries/$shipment->tracking_id/state-history");
            $body = json_decode($response->getBody()->getContents()) ;

            return (array)$body ?? null ;
        });
    }

    public function connection($connectionParams = null)
    {
        return new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => $this->api_key,
            ],
            'base_uri' => $this->api_uri,
        ]);
    }

    private function setBody($order_id)
    {
        $order_object = $this->order_repository->get($order_id, [], 'id', ['address', 'user.client']);

        throw_if(!isset($order_object->user->client), new ShipmentException([
            'bosta' => ['user has not client entity']
        ]));

        $address_object = $order_object->address;

        $user_object = $order_object->user;

        $client_object = $user_object->client;

        return array_filter($this->bosta_request->toArray($client_object, $user_object, $address_object, $order_object));
    }

    public function handleError($callback)
    {
        try {
            return $callback();
        } catch (Exception $exception) {
            if ($exception->getCode() !== 404) {
                throw new ShipmentException([
                    'bosta' => [$exception->getMessage()]
                ]);
            }

        }
    }
}
