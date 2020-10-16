<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Facades\UsersErrorsHelper;

class AccountRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';

        switch ($this->getMethod()) {
            case 'GET':
            case 'DELETE':
                $default = [
                    'user' => 'required|integer|exists:users,id' . $delete_check,
                ];
                break;
            case 'POST':
                $default = [
                    'first_name' => 'required|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',
                    'family_name' => 'required|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',

                    'email' => 'required|email:rfc,filter|unique:users,email',
                    'password' => 'required|string|min:6',

                    'account_type_id' => 'required|integer|exists:user_types,id' . $delete_check,

                    'alternative_email' => 'nullable|email:rfc,filter',
                    'is_active' => 'boolean',
                    'phone_number' => 'required|string|min:4|max:14',

                    'country_id' => 'required|integer|exists:countries,id' . $delete_check,
                    'gender_id' => 'required|integer|exists:genders,id' . $delete_check,
                    'title_id' => 'required|integer|exists:titles,id' . $delete_check,
                    'educational_level_id' => 'required|integer|exists:educational_levels,id' . $delete_check,
                    'educational_degree_id' => 'required|integer|exists:educational_degrees,id' . $delete_check,

                    'educational_field' => 'nullable|string|max:255',
                    'university' => 'nullable|string|max:255',
                    'faculty' => 'nullable|string|max:255',
                    'fax_number' => 'nullable|string|max:255',
                    'address' => 'nullable|string|max:255',
                ];
                break;
            case 'PUT':
                $default = [
                    'user' => 'required|integer|exists:users,id' . $delete_check,

                    'first_name' => 'nullable|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',
                    'family_name' => 'nullable|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',

                    'account_type_id' => 'nullable|integer|exists:user_types,id' . $delete_check,

                    'email' => 'nullable|email:rfc,filter|unique:users,email,'.$this->user,
                    'alternative_email' => 'nullable|email:rfc,filter',
                    'is_active' => 'nullable|boolean',
                    'phone_number' => 'nullable|string|min:4|max:14',

                    'country_id' => 'nullable|integer|exists:countries,id' . $delete_check,
                    'gender_id' => 'nullable|integer|exists:genders,id' . $delete_check,
                    'title_id' => 'nullable|integer|exists:titles,id' . $delete_check,
                    'educational_level_id' => 'nullable|integer|exists:educational_levels,id' . $delete_check,
                    'educational_degree_id' => 'nullable|integer|exists:educational_degrees,id' . $delete_check,

                    'educational_field' => 'nullable|string|max:255',
                    'university' => 'nullable|string|max:255',
                    'faculty' => 'nullable|string|max:255',
                    'fax_number' => 'nullable|string|max:255',
                    'address' => 'nullable|string|max:255',
                ];
                break;

            default:
                $default = [];
                break;
        }

        return $default;
    }

    public function attributes()
    {
        return [
            'user' => 'الحساب',
            'first_name' => 'الاسم الاول',
            'family_name' => 'اسم العائلة',

            'email' => 'البريد الالكتروني',
            'alternative_email' => 'البريد الالكتروني البديل',
            'is_active' => 'التفعيل',
            'phone_number' => 'رقم الموبايل',

            'account_type_id' => 'نوع الحساب',

            'country_id' => 'الدولة',
            'gender_id' => 'النوع',
            'title_id' => 'اللقب',
            'educational_level_id' => 'المستوى التعليمي',
            'educational_degree_id' => 'الدرجة العلمية',

            'educational_field' => '',
            'university' => 'الجامعة',
            'faculty' => 'الكلية',
            'fax_number' => 'رقم الفاكس',
            'address' => 'العنوان',
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

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'user');
    }
}
