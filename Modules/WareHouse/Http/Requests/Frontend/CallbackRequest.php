<?php

namespace Modules\WareHouse\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'success' => 'sometimes',
            'order_id' => 'sometimes|exists:orders,payment_order_id',
            'amount_cents' => 'sometimes|required_with:success|numeric',
        ];
    }
}
