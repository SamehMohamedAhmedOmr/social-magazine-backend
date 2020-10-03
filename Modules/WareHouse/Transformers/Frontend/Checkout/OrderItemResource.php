<?php

namespace Modules\WareHouse\Transformers\Frontend\Checkout;

use Illuminate\Http\Resources\Json\Resource;
use Modules\WareHouse\Transformers\Frontend\Cart\ToppingResource;

class OrderItemResource extends Resource
{
    public function toArray($request)
    {
        // $images = $this->prepareImages($this->images);

        $name = null;
        $description = null;
        $slug = null;
        $product_id = null;
        $weight = null;
        $sku = null;

        if ($this->relationLoaded('product')){
            if ($this->product){
                $currentLanguage = $this->product->currentLanguage;
                $name = ($currentLanguage) ? $currentLanguage->name : null;
                $description = ($currentLanguage) ? $currentLanguage->description : null;
                $slug = ($currentLanguage) ? $currentLanguage->slug : null;

                $product_id = $this->product->id;
                $weight = $this->product->weight;
                $sku = $this->product->sku;
            }
        }

        $favorite = (count($this->product->favorites)) ? true : false;
        return [
            'order_item_id' => $this->id,
            'product_id' => $product_id,

            'quantity' => $this->quantity,
            'has_toppings' => $this->has_toppings,

            'is_active' => $this->is_active,

            'name' => $name,
            'description' => $description,
            'slug' => $slug,

            "weight" => $weight,
            "sku" => $sku,
            'price' => $this->price,
            //  'images' => $images,
            'favourite' => $favorite,

            'toppings' => OrderItemToppingResource::collection($this->toppings),
        ];
    }


    private function prepareImages($images)
    {
        $data = [];
        foreach ($images as $image) {
            $data['images'][] = getImagePath('products', $image->image);
        }
        return $data;
    }
}
