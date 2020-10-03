<?php

namespace Modules\Loyality\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class ValidatePointsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'total_price' => 'required|numeric|min:0|not_in:0',
            'points_needed' => 'required|integer|min:1',
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
