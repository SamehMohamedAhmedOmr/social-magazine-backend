<?php

namespace Modules\WareHouse\Transformers\Frontend\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\WareHouse\Facades\CartHelper;

class ToppingResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        list($name, $slug, $description) = CartHelper::prepareLanguages($this->languages);
        //    $images = CartHelper::prepareImages($this->images);
        $price = CartHelper::preparePrice($this->priceLists);

        list($stock_quantity) = CartHelper::getTargetWarehouse($this->warehouse, $this->id);

        return [

            'topping_id' => $this->id,

            'name' => $name,
            'description' => $description,
            'slug' => $slug,

            'price' => $price,
          //  'images' => $images,
            "sku" => $this->sku,
            'is_active' => $this->is_active,
            'stock_qty' => $stock_quantity,
        ];
    }
}
