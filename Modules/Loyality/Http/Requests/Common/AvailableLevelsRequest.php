<?php

namespace Modules\Loyality\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AvailableLevelsRequest extends FormRequest
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
            'promocode' => 'string'
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
