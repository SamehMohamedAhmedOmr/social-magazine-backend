<?php

namespace Modules\Loyality\Transformers\CMS;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Transformers\CMS\Product\ProductNamesResource;

class LoyalityProductResource extends Resource
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
            'product_id' => $this->product_id,
            'languages' => ProductNamesResource::collection($this->product->languages),
            'sku' => $this->product->sku,
            'weight' => $this->weight,
            'is_active' => (boolean)$this->is_active,
        ];
    }
}
