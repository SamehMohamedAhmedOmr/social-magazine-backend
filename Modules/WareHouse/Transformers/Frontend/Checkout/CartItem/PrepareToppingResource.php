<?php

namespace Modules\WareHouse\Transformers\Frontend\Checkout\CartItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Facades\ProductHelper;
use Modules\WareHouse\Facades\CartHelper;

class PrepareToppingResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        $price = ProductHelper::price($this);
        $buyingPrice = ProductHelper::buyingPrice($this->product);

        list($stock_quantity) = CartHelper::getTargetWarehouse($this->warehouse, $this->id);

        return [

            'topping_id' => $this->id,

            'name' => $this->currentLanguage->name ?? '',
            'description' => $this->currentLanguage->description ?? '',
            'slug' => $this->currentLanguage->slug ?? '',

            'price' => $price,
            'buying_price' => $buyingPrice,

          //  'images' => $images,
            "sku" => $this->sku,
            'is_active' => $this->is_active,
            'stock_quantity' => $stock_quantity,
        ];
    }
}
