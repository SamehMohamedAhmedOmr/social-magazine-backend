<?php

namespace Modules\WareHouse\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class PurchaseInvoiceResource extends Resource
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
            'total_price' => $this->total_price,
            "status" => isset($this->status) ? $this->status : $this->added_status,
            'date' => $this->created_at->todatestring(),
            'payment_entry' => PaymentEntryResource::collection($this->paymentEntry)
        ];
    }
}
