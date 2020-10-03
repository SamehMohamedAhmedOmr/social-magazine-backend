<?php

namespace Modules\WareHouse\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class PrepareProductQuantityResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [];
    }

    public static function prepare($data)
    {
        $now = Carbon::now()->toDateTimeString();
        return [
            'product_id' => $data['product_id'],
            'projected_quantity' => $data['projected_quantity'],
            'warehouse_id' => $data['warehouse_id'],
            'created_at' => $now,
            'updated_at' => $now,
            'available' => $data['available'],
        ];
    }
}
