<?php

namespace Modules\WareHouse\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class PrepareStockResource extends Resource
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

        ];
    }

    public static function prepare($data)
    {
        $now = Carbon::now()->toDateTimeString();
        return [
            'product_id' => $data['product_id'],
            'stock_quantity' => $data['stock_quantity'],
            'from_warehouse' => $data['from_warehouse'],
            'to_warehouse' => $data['to_warehouse'],
            'type' => $data['type'],
            'is_active' => $data['is_active'],
            'purchase_order_id' => $data['purchase_order_id'],
            'company_id' => $data['company_id'],
            'file_name' => $data['file_name'],
            'user_id' => $data['user_id'],
            'created_at' => $now,
            'updated_at' => $now
        ];
    }
}
