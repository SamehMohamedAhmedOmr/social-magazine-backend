<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class ShippingRulesResource extends Resource
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
            'shipping_rule_label' => $this->shipping_rule_label,
            'key' => $this->key,
            'price' => $this->price,
            'is_active' => $this->is_active,
        ];
    }
}
