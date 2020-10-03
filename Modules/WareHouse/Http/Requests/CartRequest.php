<?php

namespace Modules\WareHouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Modules\WareHouse\Rules\CartToppingRule;
use Illuminate\Validation\Rule;

class CartRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';
        return [
            'products' => 'required|array',
            'products.*.cart_item_id' => 'integer|exists:cart_items,id',
            'products.*.id' => 'required|integer|exists:products,id'.$delete_check,
            'products.*.quantity' => 'required|integer|max:100000',
            'products.*.toppings' => 'array',
            'products.*.toppings.*' => [
                'bail',
                'required',
                'integer',
                Rule::exists('products', 'id')->where(function ($query) {
                    $query->where('is_topping', 1);
                }),
                App::make(CartToppingRule::class)
            ]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
