<?php

namespace Modules\Users\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\WareHouse\Transformers\Frontend\Checkout\OrderResource;

class UserSummaryResource extends Resource
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
            'name' => $this->name,
            'phone' => ($this->client) ? $this->client->phone : '',
            'addresses' => AddressResource::collection($this->whenLoaded('address')),
            'orders' => OrderResource::collection($this->whenLoaded('orders'))
        ];
    }
}
