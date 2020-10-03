<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemSettingRequest extends FormRequest
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
            'minimum_selling_price' => 'required|numeric|between:0,99999999.99',
            'maximum_selling_price' => 'required|numeric|between:0,99999999.99',
            'free_shipping_minimum_price' => 'required|numeric|between:0,99999999.99',
            'is_free_shipping' => 'required|boolean',
            'country_id' => 'integer|exists:countries,id'.$delete_check,
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
