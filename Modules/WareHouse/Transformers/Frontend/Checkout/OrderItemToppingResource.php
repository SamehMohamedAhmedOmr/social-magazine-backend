<?php

namespace Modules\WareHouse\Transformers\Frontend\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class OrderItemToppingResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
//        $languages = $this->prepareLanguages($this->languages);
        //    $images = $this->prepareImages($this->images);

        $name = null;
        $description = null;
        $slug = null;

        if ($this->relationLoaded('currentLanguage')){
            if ($this->currentLanguage){
                $currentLanguage = $this->currentLanguage;
                $name = ($currentLanguage) ? $currentLanguage->name : null;
                $description = ($currentLanguage) ? $currentLanguage->description : null;
                $slug = ($currentLanguage) ? $currentLanguage->slug : null;
            }
        }

        return [

            'topping_id' => $this->id,

            'name' => $name,
            'description' => $description,
            'slug' => $slug,

            'price' => $this->pivot->price,
          //  'images' => $images,
            "sku" => $this->sku,
            'is_active' => $this->is_active,
        ];
    }

    private function prepareLanguages($item)
    {
        $name = count($item) ? $item[0]->name : '';
        $slug = count($item) ? $item[0]->slug : '';
        $description = count($item) ? $item[0]->description : '';

        $data['name'] = $name;
        $data['slug'] = $slug;
        $data['description'] = $description;

        return $data;
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
