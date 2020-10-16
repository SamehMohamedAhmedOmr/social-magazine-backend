<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed email
 * @property mixed password
 * @property mixed remember_me
 */
class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,filter|exists:users,email',
            'password' => 'required|string',
            'remember_me' => 'in:0,1'
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'البريد الالكتروني',
            'password' => 'كلمة السر',
            'remember_me' => 'تذكرني'
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
