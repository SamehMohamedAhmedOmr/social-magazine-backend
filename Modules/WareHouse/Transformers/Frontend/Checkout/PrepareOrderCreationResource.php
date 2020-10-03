<?php

namespace Modules\WareHouse\Transformers\Frontend\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class PrepareOrderCreationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [

        ];
    }

    public static function prepare($data)
    {
        return [
            'delivery_date' => $data['delivery_date'],

            'shipping_price' => $data['shipping_price'],

            'total_price' => $data['total_price'],

            'discount' => $data['discount'],

            'vat' => $data['vat'],

            'device_id' => $data['device_id'],
            'device_os' => $data['device_os'],
            'app_version' => $data['app_version'],

            'user_id' => $data['user_id'],
            'payment_method_id' => $data['payment_method_id'],
            'address_id' => $data['address_id'],
            'time_section_id' => $data['time_section_id'],
            'shipping_rule_id' => $data['shipping_rule_id'],
            'warehouse_id' => $data['warehouse_id'],
            'loyality_discount' =>  $data['loyality_discount'],
            'status_id' => $data['status'],
        ];
    }
}
