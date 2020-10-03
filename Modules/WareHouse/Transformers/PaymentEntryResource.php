<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class PaymentEntryResource extends Resource
{
    private $added_status = 0;
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
            'payment_entry_type' => $this->PaymentEntryType->key,
            'payment_price' => $this->payment_price,
            'payment_reference' => $this->payment_reference,
            "status" => isset($this->status) ? $this->status : $this->added_status,
        ];
    }
}
