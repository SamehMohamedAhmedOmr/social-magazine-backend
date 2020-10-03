<?php

namespace Modules\WareHouse\Transformers\CMS\Shipment;

class BostaRequestResource
{
    public function toArray($client_object, $user_object, $address_object, $order_object)
    {
        $district = $address_object->district
            ? $address_object->district->language()->first()
            : null;

        return [
            'receiver' => [
                'firstName' => $user_object->name,
                'lastName' => 'N/A',
                'email' => $user_object->email,
                'phone' => '+2' . $client_object->phone,
            ],
            'dropOffAddress' => [
                'firstLine' => $address_object->title . ($address_object->street ?: '') .
                    ($district != null ? $district->name : ''),
                'secondLine' => $address_object->street . '- ' . $address_object->nearest_landmark,
                'floor' => isset($address_object->floor_no) ? $address_object->floor_no : 'N/A',
                'apartment' => isset($address_object->apartment_no) ? $address_object->apartment_no : 'N/A',
                'zone' => isset($address_object->city) ? $address_object->city : 'NA',
                'district' => $district != null ? $district->name : 'N/A',
            ],
            'pickupAddress' => [
                'firstLine' => config('warehouse.shipments.bosta.pickup_address.address'),
                'floor' => config('warehouse.shipments.bosta.pickup_address.floor'),
                'apartment' => config('warehouse.shipments.bosta.pickup_address.apartment'),
                'zone' => config('warehouse.shipments.bosta.pickup_address.zone'),
                'district' => config('warehouse.shipments.bosta.pickup_address.district'),
            ],
            'returnAddress' => [
                'firstLine' => config('warehouse.shipments.bosta.return_address.address'),
                'floor' => config('warehouse.shipments.bosta.return_address.floor'),
                'apartment' => config('warehouse.shipments.bosta.return_address.apartment'),
                'zone' => config('warehouse.shipments.bosta.return_address.zone'),
                'district' => config('warehouse.shipments.bosta.return_address.district'),
            ],
            'note' => $order_object->note,
            'type' => config('warehouse.shipments.bosta.delivery_type'),
            'webhookUrl' => config('warehouse.shipments.bosta.webhook_url'),
            'cod' => $order_object->total_price + $order_object->shipping_rate - $order_object->discount
        ];
    }
}
