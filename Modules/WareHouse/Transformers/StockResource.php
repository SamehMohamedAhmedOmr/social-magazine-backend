<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Settings\Transformers\CompanyResource;

class StockResource extends Resource
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
            'stock_quantity' => $this->stock_quantity,
            'type' => ($this->type) ? trans('warehouse::msg.moved')  : trans('warehouse::msg.added') ,
            'from_warehouse' => ($this->whenLoaded('fromWarehouse')) ? $this->whenLoaded('fromWarehouse')->currentLanguage->name : '',
            'to_warehouse' => ($this->whenLoaded('toWarehouse')) ? $this->whenLoaded('toWarehouse')->currentLanguage->name : '',
            'purchase_order_id' => $this->purchase_order_id,
            'product_id' => $this->product_id,
            'date' => $this->created_at->diffForHumans(),
            'company' => CompanyResource::make($this->whenLoaded('company'))
        ];
    }
}
