<?php

namespace Modules\Loyality\Http\Requests\CMS;

use Illuminate\Foundation\Http\FormRequest;

class LoyaliyProgramRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required_or_not = request()->method == 'POST' ? 'required' : 'sometimes';
        return [
            'price_to_points' => $required_or_not.'|integer|min:1|max:1048576', // 2^20 :D
            'point_to_price' => $required_or_not.'|numeric|min:0|not_in:0|max:1048576', // for anything > 0 but not 0
            'max_allowed_points' => $required_or_not.'|min:1|max:1048576',
            'min_allowed_points' => $required_or_not.'|min:1|max:1048576|lt:max_allowed_points',
            'points_option' => $required_or_not.'|in:price,percentage',
            'days_until_refund' => $required_or_not.'|integer|min:0|max:365', // 365 days
            'days_until_expiration' => $required_or_not.'|integer|min:1',
            'is_levels' => 'sometimes|boolean',
            'levels' => 'array',
            'levels.*' => 'integer|min:1|max:999999',
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
