<?php

namespace Modules\WareHouse\Transformers\CMS\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;


class ProductWarehouseStockResource extends Resource
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
            'id' => $this->id ,
            'is_default' => $this->default_warehouse,
            'name' => ($this->whenLoaded('currentLanguage')) ? $this->whenLoaded('currentLanguage')->name : '',
            'quantity' => $this->pivot->projected_quantity,
            'variations' => ProductWarehouseStockResource::collection($this->whenLoaded('variations'))
        ];
    }
}
