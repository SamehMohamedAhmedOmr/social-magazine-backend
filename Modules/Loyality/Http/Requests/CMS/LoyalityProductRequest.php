<?php

namespace Modules\Loyality\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class LoyalityProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required_or_not = request()->method == 'POST' ? 'required' : 'sometimes';
        $common = [
            'weight' => $required_or_not.'|numeric|min:0|not_in:0|max:1048576', // for anything > 0 but not 0
            'is_active' => $required_or_not.'|boolean',
        ];

        return request()->method == 'POST'
            ? array_merge($common, [ 'product_id' => 'required|integer|unique:loyality_products,id' ])
            : $common ;
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
