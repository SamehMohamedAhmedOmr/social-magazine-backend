<?php

namespace Modules\FrontendUtilities\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromocodeValidateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'promocode' => 'required|string|exists:promocodes,code,deleted_at,NULL',
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
