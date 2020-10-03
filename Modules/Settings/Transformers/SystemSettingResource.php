<?php

namespace Modules\Settings\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class SystemSettingResource extends Resource
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
            'minimum_selling_price' => $this->minimum_selling_price,
            'maximum_selling_price' => $this->maximum_selling_price,
            'is_free_shipping' => $this->is_free_shipping,
            'free_shipping_minimum_price' => $this->free_shipping_minimum_price,
        ];
    }
}
