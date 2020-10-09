<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Facades\UsersErrorsHelper;

class RegisterRequest extends FormRequest
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
            // Basic Information
            'first_name' => 'required|string|regex:'.UsersErrorsHelper::regexName().'|max:255',
            'family_name' => 'required|string|regex:'.UsersErrorsHelper::regexName().'|max:255',

            'email' => 'required|email:rfc,filter|unique:users,email',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|string|min:4|max:14',

            'country_id' => 'required|integer|exists:countries,id'.$delete_check,
            'gender_id' => 'required|integer|exists:genders,id'.$delete_check,
            'title_id' => 'required|integer|exists:titles,id'.$delete_check,
        ];
    }


    public function attributes()
    {
        return [
            'first_name' => 'الاسم الاول',
            'family_name' => 'اسم العائلة',
            'phone_number' => 'رقم الهاتف',
            'country_id' => 'الدولة',
            'gender_id' => 'النوع',
            'title_id' => 'اللقب',
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
