<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\WareHouse\Transformers\CountryResource;

class PriceListResource extends Resource
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
            'price_list_name' => $this->price_list_name,
            'key' => $this->key,
            'is_special' => $this->is_special,
            'is_active' => $this->is_active,

            'currency' => CurrencyResource::make($this->whenLoaded('currency')),

            'type' => PriceListTypeResource::make($this->whenLoaded('PriceListType')),

            'country' =>  CountryResource::make($this->whenLoaded('country')),
        ];
    }
}
