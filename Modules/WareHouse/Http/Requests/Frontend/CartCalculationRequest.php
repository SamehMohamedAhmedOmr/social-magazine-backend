<?php

namespace Modules\WareHouse\Http\Requests\Frontend;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class CartCalculationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address_id' => [
                'integer',
                Rule::exists('address', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id())->where('deleted_at', null);
                }),
            ],
            'promocode' => 'string',
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
