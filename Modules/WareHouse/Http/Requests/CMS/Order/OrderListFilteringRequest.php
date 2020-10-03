<?php

namespace Modules\WareHouse\Http\Requests\CMS\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderListFilteringRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'product_id' => 'integer',
            'order_id' => 'integer',
            'user_id' => 'integer',
            'district_id' => 'integer',
            'payment_method_id' => 'integer',

            'status' => 'integer',
            'from' => 'date',
            'to' => 'date'
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
