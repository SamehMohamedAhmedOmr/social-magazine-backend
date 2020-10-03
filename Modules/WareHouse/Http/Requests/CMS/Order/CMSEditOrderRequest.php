<?php

namespace Modules\WareHouse\Http\Requests\CMS\Order;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;
use Modules\WareHouse\Rules\CartToppingRule;
use Illuminate\Support\Facades\App;

class CMSEditOrderRequest extends FormRequest
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
            'order' => 'required|integer|exists:orders,id'.$delete_check,
            'status' => 'sometimes|integer|exists:order_statuses,key',

            'payment_method' => 'integer|exists:payment_methods,id'.$delete_check,

            'user_id' => 'integer|exists:users,id'.$delete_check,

            'address_id' => [
                'integer',
                Rule::exists('address', 'id')->where(function ($query) {
                    $query->where('user_id', request('user_id'))->where('deleted_at', null);
                }),
            ],

            'delivery_date' => 'date_format:Y-m-d|after_or_equal:'.$now->toDateString(),
            'time_section' => 'integer|exists:time_sections,id'.$delete_check,

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

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, ['data'], 'order');
    }
}
