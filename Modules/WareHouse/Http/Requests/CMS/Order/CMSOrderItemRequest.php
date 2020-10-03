<?php

namespace Modules\WareHouse\Http\Requests\CMS\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CMSOrderItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';

        if (request()->getMethod() == 'POST') {
            return [
                'order_id' => 'required|integer|exists:orders,id'.$delete_check,
                'product_id' => 'required|integer|exists:products,id'.$delete_check,
                'quantity' => 'required|integer|min:1|max:100000',
                'toppings' => 'array',
                'toppings.*' => [
                    'bail',
                    'required',
                    'distinct',
                    'integer',
                    Rule::exists('products', 'id')->where(function ($query) {
                        $query->where('is_topping', 1);
                    })
                ]
            ];
        }
        if (request()->getMethod() == 'DELETE') {
            return [
                'order_item_id' => 'required|integer|exists:order_items,id'.$delete_check,
            ];
        }
        return [];
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

    protected function prepareForValidation()
    {
        $input = $this->all();
        if (request()->getMethod() == 'DELETE') {
            $input['order_item_id'] = request('order_item_id');
        }
        $this->replace($input);
    }
}
