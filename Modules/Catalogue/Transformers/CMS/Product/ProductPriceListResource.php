<?php

namespace Modules\Catalogue\Transformers\CMS\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Settings\Transformers\PriceListResource;

class ProductPriceListResource extends Resource
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
            'price' => $this->pivot->price,
            'price_list' => PriceListResource::make($this),
        ];
    }
}
