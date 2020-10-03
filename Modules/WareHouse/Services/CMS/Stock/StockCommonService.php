<?php

namespace Modules\WareHouse\Services\CMS\Stock;

use Modules\WareHouse\Transformers\PrepareProductImportingListResource;
use Modules\WareHouse\Transformers\PrepareProductQuantityResource;
use Modules\WareHouse\Transformers\PrepareStockResource;

class StockCommonService
{

    public function __construct()
    {

    }

    /**
     * @param $product_id
     * @param $quantity
     * @param $to_warehouse
     * @param $from_warehouse
     * @param $file_path
     * @param $type
     * @return array
     */
    public function prepareStockData($product_id, $quantity, $to_warehouse, $from_warehouse, $file_path, $type)
    {
        return PrepareStockResource::prepare([
            'product_id' => $product_id,
            'stock_quantity' => $quantity,
            'from_warehouse' => $from_warehouse,
            'to_warehouse' => $to_warehouse,
            'type' => $type,
            'is_active' => 1,
            'purchase_order_id' => null,
            'company_id' => null,
            'file_name' => $file_path,
            'user_id' => \Auth::id(),
        ]);
    }


    public function prepareProductsQuantity($product, $quantity, $warehouse, $is_sell_with_availability)
    {
        return PrepareProductQuantityResource::prepare([
            'product_id' => $product,
            'projected_quantity' => $quantity,
            'warehouse_id' => $warehouse,
            'available' => is_bool($is_sell_with_availability) ? $is_sell_with_availability : 0,
        ]);
    }


    public function prepareProductList($product, $warehouse_id, $new_quantity, $old_quantity, $status, $reason = null)
    {
        return PrepareProductImportingListResource::make(collect([
            'product_id' => $product,
            'warehouse_id' => $warehouse_id,
            'new_quantity' => $new_quantity,
            'old_quantity' => $old_quantity,
            'status' => $status,
            'reason' => $reason
        ]));
    }


    public function newQuantity($product_qty, $quantity, $type = 'ADDED')
    {
        if ($type == 'ADDED') {
            $old_quantity = ($product_qty) ?  $product_qty->projected_quantity : 0;

            $newQuantity = $old_quantity + $quantity;
        } else { // type 'MOVED'
            $old_quantity = $product_qty->projected_quantity;
            $newQuantity =  $old_quantity - $quantity;
        }

        return [$newQuantity,$old_quantity];
    }

}
