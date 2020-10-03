<?php

namespace Modules\WareHouse\Transformers\Frontend\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Facades\ProductHelper;
use Modules\WareHouse\Facades\CartHelper;

class CartResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $topping = CartHelper::attachWarehouseToTopping($this->toppings, $this->warehouse);

        $price = ProductHelper::price($this->product);

        $has_toppings = ($this->has_toppings) ? true : false;
        list($stock_quantity) = CartHelper::getTargetWarehouse($this->warehouse, $this->product->id);

        $cart_resource = new CartItemResource(
            $this->product,
            $topping,
            $this->id,
            $this->quantity,
            $has_toppings,
            $stock_quantity,
            $price
        );

        return  $cart_resource->prepare();
    }
}
