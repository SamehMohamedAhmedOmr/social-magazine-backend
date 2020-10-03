<?php

namespace Modules\Loyality\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class CalculatePointsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'products' => 'required|array',
            'products.*.id' => 'required|integer',
            'products.*.price' => 'required|numeric|min:0|not_in:0|max:1048576'

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
