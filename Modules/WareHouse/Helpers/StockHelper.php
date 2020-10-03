<?php

namespace Modules\WareHouse\Helpers;

use Illuminate\Validation\ValidationException;

/**
 * Class CartHelper
 * @package Modules\WareHouse\Helpers
 */
class StockHelper
{
    /**
     * @return mixed
     * @throws ValidationException
     */
    public function NoAvailableQuantity()
    {
        throw ValidationException::withMessages([
            'price' => trans('warehouse::errors.NoAvailableQuantity'),
        ]);
    }

    public function  addProductNotification($notifications, $products_id, $product_repository){

        $products = $product_repository->getBulk('id', $products_id);
        $products->load('currentLanguage');

        foreach ($products as $product){
            $product_name = ($product->currentLanguage) ? $product->currentLanguage->name : trans('warehouse::msg.Product');
            $notifications->push([
                'product_id' => $product->id,
                'title' => $product_name,
                'body' => $product_name . ' ' . trans('warehouse::msg.available_now'),
            ]);
        }

        return $notifications;
    }



}
