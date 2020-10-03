<?php

namespace Modules\Catalogue\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Class CheckoutErrorsHelper
 * @package Modules\WareHouse\Helpers
 */
class ProductHelper
{
    public function stockQuantity($product)
    {
        if (!$product) {
            return 0;
        }

        $quantity = 0;

        $productWarehouses = $product->warehouses->whereIn(
            'warehouse_id',
            Session::get(
                'warehouses_id'
            )
        );

        if ((boolean)$product->is_sell_with_availability) {

            $productWarehouses = $productWarehouses->where('available', true);

            if (count($productWarehouses)) return $product->max_quantity_per_order;

        }


        foreach ($productWarehouses as $productWarehouse){
            $quantity += $productWarehouse->projected_quantity;
        }

        return $quantity;
    }

    public function price($product)
    {
        $price = $product->price->first();

        return $price === null ? 0 : (float) $price->pivot->price;
    }

    public function buyingPrice($product)
    {
        $price = $product->buyingPrice->first();

        return $price === null ? 0 : (float) $price->pivot->price;
    }

    public function favorite($product)
    {
        return Auth::check()
            ? (Auth::user()->favorites()->where('product_id', $product->id)->first() ? true : false) : null;
    }
}
