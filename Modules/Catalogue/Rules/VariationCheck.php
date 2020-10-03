<?php

namespace Modules\Catalogue\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Modules\Catalogue\Entities\Product;

class VariationCheck implements Rule
{
    private $request;

    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
        $variations = $this->request->variations ?? [];

        if ($variations != []) {
            $sku_existence = [];
            if ($this->request->has('sku')) {
                $sku_existence[$this->request->sku] = 1;
            }

            foreach ($variations as $variation) {
                // SKU
                if (array_key_exists('sku', $variation)) {
                    if (isset($sku_existence[$variation['sku']])) {
                        $this->message = 'SKU should be unique';
                        return false;
                    } else {
                        $sku_existence[$variation['sku']] = 1;
                    }
                }

                // Topping
                if (array_key_exists('is_topping', $variation) && array_key_exists('topping_menu_id', $variation)
                    && $variation['is_topping'] && $variation['topping_menu_id'] != null) {
                    $this->message = 'is_topping cannot be send with topping_menu_id';
                    return false;
                }

                // Parent
                if (array_key_exists('id', $variation)) {
                    $product_variation = Product::find($variation['id']);
                    if (!$product_variation) {
                        $this->message = 'id does not exist';
                        return false;
                    }
                    if ($product_variation->parent_id != null && $product_variation->parent_id != $this->request->product) {
                        $this->message = 'only product\'s children can be updated';
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'in :attribute, '. $this->message;
    }
}
