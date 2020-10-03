<?php

namespace Modules\WareHouse\Services\CMS\Shipment\Companies;

use GuzzleHttp\Client;
use Modules\Users\Repositories\ClientRepository;
use Modules\WareHouse\Repositories\OrderRepository;
use Modules\WareHouse\Transformers\CMS\Shipment\AramexRequestResource;
use Modules\WareHouse\Transformers\CMS\Shipment\BostaRequestResource;

class Shipment
{
    protected $shipping_company;
    protected $client_repository;
    protected $order_repository;
    protected $bosta_request;
    protected $aramex_request;

    public function __construct(
        ClientRepository $client_repository,
        BostaRequestResource $bosta_request,
        AramexRequestResource $aramex_request,
        OrderRepository $order_repository,
        Client $http
    ) {
        $this->bosta_request = $bosta_request;
        $this->aramex_request = $aramex_request;
        $this->client_repository = $client_repository;
        $this->order_repository = $order_repository;
    }

    public function setShipmentCompany($company)
    {
        switch ($company) {
            case 'bosta':
                $this->shipping_company = new Bosta($this->client_repository, $this->bosta_request, $this->order_repository);
                break;
            case 'aramex':
                $this->shipping_company = new Aramex($this->client_repository, $this->aramex_request, $this->order_repository);
                break;
        }
    }

    public function send($order_id)
    {
        return $this->shipping_company->create($order_id);
    }

    public function getShipmentReceipt($shipment_id)
    {
        return $this->shipping_company->getShipmentReceipt($shipment_id);
    }

    public function track($shipment)
    {
        return $this->shipping_company->track($shipment);
    }
}
