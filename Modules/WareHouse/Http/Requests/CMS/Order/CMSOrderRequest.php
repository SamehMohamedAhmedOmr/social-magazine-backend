<?php

namespace Modules\WareHouse\Http\Requests\CMS\Order;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;
use Modules\WareHouse\Rules\CartToppingRule;
use Illuminate\Support\Facades\App;

class CMSOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';
        $now = Carbon::now();
        return [
            'payment_method' => 'required|integer|exists:payment_methods,id'.$delete_check,
            'user_id' => 'required|integer|exists:users,id'.$delete_check,

            'address_id' => [
                'required',
                'integer',
                Rule::exists('address', 'id')->where(function ($query) {
                    $query->where('user_id', request('user_id'))->where('deleted_at', null);
                }),
            ],

            'delivery_date' => 'date_format:Y-m-d|after_or_equal:'.$now->toDateString(),
            'time_section' => 'integer|exists:time_sections,id'.$delete_check,
            'promocode' => 'string',

            'products' => 'required|array',
            'products.*.product_id' => 'required|integer|distinct|exists:products,id'.$delete_check,
            'products.*.quantity' => 'required|integer|max:100000',
            'products.*.toppings' => 'array',
            'products.*.toppings.*' => [
                'bail',
                'required',
                'distinct',
                'integer',
                Rule::exists('products', 'id')->where(function ($query) {
                    $query->where('is_topping', 1);
                }),
                App::make(CartToppingRule::class)
            ],
            'level_id' => 'sometimes|integer',
            'points_needed' => 'sometimes|integer|max:10000',
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
