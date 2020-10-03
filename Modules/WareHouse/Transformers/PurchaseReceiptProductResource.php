<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Catalogue\Transformers\CMS\Product\ProductNamesResource;

class PurchaseReceiptProductResource extends Resource
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
            'accepted_quantity' => $this->pivot->accepted_quantity,
            'remaining_quantity' => $this->pivot->remaining_quantity,
            'requested_quantity' => $this->pivot->requested_quantity,
            'languages' => ProductNamesResource::collection($this->languages)
        ];
    }
}
