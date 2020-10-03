<?php

namespace Modules\WareHouse\Transformers\CMS\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;


class ProductStockResource extends Resource
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
            'stock_warehouses' => ProductWarehouseStockResource::collection($this->whenLoaded('availableWarehouses')),
            'variations' => ProductStockResource::collection($this->whenLoaded('variations')),
        ];
    }
}
