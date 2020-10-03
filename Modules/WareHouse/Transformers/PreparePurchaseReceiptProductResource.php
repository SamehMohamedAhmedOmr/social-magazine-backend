<?php

namespace Modules\WareHouse\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class PreparePurchaseReceiptProductResource extends Resource
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

    public static function prepare($data)
    {
        $now = Carbon::now()->toDateTimeString();
        return [
            'product_id' => $data['product_id'],
            'accepted_quantity' => $data['accepted_quantity'],
            'requested_quantity' => $data['requested_quantity'],
            'remaining_quantity' => $data['remaining_quantity'],
            'created_at' => $now,
            'updated_at' => $now
        ];
    }
}
