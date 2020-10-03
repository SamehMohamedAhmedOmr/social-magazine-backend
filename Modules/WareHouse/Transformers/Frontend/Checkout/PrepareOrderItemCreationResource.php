<?php

namespace Modules\WareHouse\Transformers\Frontend\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class PrepareOrderItemCreationResource extends Resource
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
            'order_id' => $data['order_id'],

            'product_id' => $data['product_id'],

            'quantity' => $data['quantity'],

            'price' => $data['price'],

            'has_toppings' => $data['has_toppings'],

            'buying_price' => $data['buying_price'],

        ];
    }
}
