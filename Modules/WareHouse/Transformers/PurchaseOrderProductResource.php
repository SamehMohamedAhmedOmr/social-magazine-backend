<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Transformers\CMS\Product\ProductNamesResource;

class PurchaseOrderProductResource extends Resource
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
            'sku' => $this->sku,
            'quantity' => $this->pivot->quantity,
            'price' => $this->pivot->price,
            'total_amount' => $this->pivot->total_amount,
            'languages' => ProductNamesResource::collection($this->languages)
        ];
    }
}
