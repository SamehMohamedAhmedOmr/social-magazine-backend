<?php

namespace Modules\WareHouse\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Catalogue\Entities\Product;

class CartToppingRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $product_attributes = explode('.', $attribute);
        if (count($product_attributes) == 4) {
            $product_index = (int)$product_attributes[1];
            if (request('products') && count(request('products'))) {
                $products = request('products');
                $target_product = $products[$product_index];
                if (isset($target_product['id'])) {
                    $target_product_id = $target_product['id'];
                    $target_product = Product::find($target_product_id);
                    if ($target_product) {
                        $target_product->load('toppingMenu.products');
                        return $this->validate($target_product, $value);
                    }
                }
            }
        }
        return true;
    }

    private function validate($target_product, $value)
    {
        if (isset($target_product->toppingMenu)) {
            $toppings = $target_product->toppingMenu->products;
            if (count($toppings)) {
                $flag = false;
                foreach ($toppings as $topping) {
                    if ($topping->id == $value) {
                        $flag = true;
                        break;
                    }
                }
                return $flag;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This topping is not related to the selected product';
    }
}
