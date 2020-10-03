<?php

namespace Modules\WareHouse\Transformers\Frontend\Checkout\CartItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class CartItemWarehouseResource extends Resource
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

            'id' => $this->id,
            'warehouse_code' => $this->warehouse_code,
            'is_active' => $this->is_active,
            "default_warehouse" => $this->default_warehouse,
        ];
    }
}
