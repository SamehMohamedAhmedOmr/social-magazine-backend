<?php

namespace Modules\WareHouse\Helpers;

use Illuminate\Validation\ValidationException;

/**
 * Class CartHelper
 * @package Modules\WareHouse\Helpers
 */
class CartHelper
{
    /**
     * @param $toppings
     * @param $warehouse
     * @return mixed
     */
    public function attachWarehouseToTopping($toppings, $warehouse)
    {
        foreach ($toppings as $topping) {
            $topping->warehouse = $warehouse;
        }
        return $toppings;
    }

    public function preparePrice($price_lists)
    {
        $price = 0;
        foreach ($price_lists as $price_list) {
            if ($price_list->key == 'STANDARD_SELLING_PRICE') {
                $price = $price_list->pivot->price;
                break;
            }
        }
        return $price;
    }

    public function getTargetWarehouse($warehouses, $product_id)
    {
        $projected_quantity = 0;
        $target_warehouse = null;
        foreach ($warehouses as $warehouse){
            $warehouse_product = $warehouse->products->where('id', $product_id)->first();
            if (isset($warehouse_product)) {
                $projected_quantity = $warehouse_product->pivot->projected_quantity;
                $target_warehouse = $warehouse;
                break;
            }
        }

        return [$projected_quantity,$target_warehouse];
    }

    public function prepareImages($images)
    {
        $data = [];
        foreach ($images as $image) {
            $data['images'][] = getImagePath('products', $image->image);
        }
        return $data;
    }

    public function prepareLanguages($languages_object)
    {
        $language_object = $languages_object->where('language_id', \Session::get('language_id'))->first();

        $name = $language_object->name;
        $slug = $language_object->slug;
        $description = $language_object->description;

        return [$name, $slug, $description];
    }
}
