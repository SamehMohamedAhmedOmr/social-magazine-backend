<?php

namespace Modules\Loyality\Transformers\Frontend;

use Illuminate\Http\Resources\Json\Resource;

class LoyalityProgramResource extends Resource
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
            'id' => $this->id,
            'price_to_get' => $this->price_to_points,
            'point_to_price' => $this->point_to_price,
            'max_allowed_points' => $this->max_allowed_points,
            'min_allowed_points' => $this->min_allowed_points,
            'is_levels' => (boolean)$this->is_levels,
        ];
    }
}
