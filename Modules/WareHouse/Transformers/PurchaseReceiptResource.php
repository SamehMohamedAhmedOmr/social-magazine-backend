<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Settings\Transformers\CompanyResource;
use Modules\Settings\Transformers\ShippingRulesResource;
use Modules\Settings\Transformers\TaxesListResource;

class PurchaseReceiptResource extends Resource
{
    private $added_status = 0;
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
            "company" => CompanyResource::make($this->company),
            "tax" => TaxesListResource::make($this->tax),
            "shipping_rule" => ShippingRulesResource::make($this->shippingRule),
            'products' => PurchaseReceiptProductResource::collection($this->products),
            "status" => isset($this->status) ? $this->status : $this->added_status,
        ];
    }
}
