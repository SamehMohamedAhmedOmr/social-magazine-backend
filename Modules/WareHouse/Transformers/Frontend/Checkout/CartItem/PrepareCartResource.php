<?php

namespace Modules\WareHouse\Transformers\Frontend\Checkout\CartItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Facades\ProductHelper;
use Modules\WareHouse\Facades\CartHelper;

class PrepareCartResource extends Resource
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

        $buyingPrice = ProductHelper::buyingPrice($this->product);


        $has_toppings = ($this->has_toppings) ? true : false;
        list($stock_quantity, $target_warehouse) = CartHelper::getTargetWarehouse($this->warehouse, $this->product->id);

        $cart_resource = new PrepareCartItemResource(
            $this->product,
            $topping,
            $this->id,
            $this->quantity,
            $has_toppings,
            $stock_quantity,
            $target_warehouse,
            $price,
            $buyingPrice
        );

        return  $cart_resource->prepare();
    }
}
