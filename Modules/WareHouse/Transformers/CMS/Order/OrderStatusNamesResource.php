<?php

namespace Modules\WareHouse\Transformers\CMS\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class OrderStatusNamesResource extends Resource
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
            'name' => $this->name,
            'lang' => $this->language->iso
        ];
    }
}
