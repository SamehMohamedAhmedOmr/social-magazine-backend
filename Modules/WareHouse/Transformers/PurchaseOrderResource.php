<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Settings\Transformers\CompanyResource;
use Modules\Settings\Transformers\ShippingRulesResource;
use Modules\Settings\Transformers\TaxesListResource;

class PurchaseOrderResource extends Resource
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
            "id" => $this->id,
            "delivery_date" => $this->delivery_date,
            "warehouse" => WarehouseResource::make($this->warehouse),
            "company" => CompanyResource::make($this->company),
            "tax" => TaxesListResource::make($this->tax),
            "shipping_rule" => ShippingRulesResource::make($this->shippingRule),
            'products' => PurchaseOrderProductResource::collection($this->products),
            "discount_type" => ($this->discount_type) ? "Percentage" : "Fixed",
            "discount" => $this->discount,
            "total_price" => $this->total_price,
            "is_active" => $this->is_active,
        ];
    }
}
