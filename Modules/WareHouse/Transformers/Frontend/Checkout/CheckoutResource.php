<?php

namespace Modules\WareHouse\Transformers\Frontend\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class CheckoutResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
