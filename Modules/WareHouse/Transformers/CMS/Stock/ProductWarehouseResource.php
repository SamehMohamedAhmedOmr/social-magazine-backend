<?php

namespace Modules\WareHouse\Transformers\CMS\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;


class ProductWarehouseResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->warehouse_id,
            'is_default' => $this->warehouse->default_warehouse,
            'name' => ($this->whenLoaded('warehouse')) ? $this->warehouse->currentLanguage->name : '',
            'quantity' => $this->projected_quantity,
            'available' => (boolean)$this->available,
        ];
    }
}
