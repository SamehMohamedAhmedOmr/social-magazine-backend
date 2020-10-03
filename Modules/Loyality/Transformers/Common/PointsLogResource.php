<?php

namespace Modules\Loyality\Transformers\Common;

use Illuminate\Http\Resources\Json\Resource;
use Modules\WareHouse\Transformers\Frontend\Checkout\OrderResource;

class PointsLogResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'points_gained' => $this->points_gained,
            'points_redeemed' => $this->points_redeemed,
            'money_spent' => (float)$this->money_spent,
            'expiration_date' => $this->expiration_date,
            'refund_date' => $this->refund_date,
            'order' => OrderResource::make($this->whenLoaded('order'))
        ];
    }
}
