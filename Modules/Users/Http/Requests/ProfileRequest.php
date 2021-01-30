<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Facades\UsersErrorsHelper;

class ProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';

        $user_id = \Auth::id();
        return [
            'first_name' => 'nullable|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',
            'family_name' => 'nullable|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',

            'email' => 'nullable|email:rfc,filter|unique:users,email,' . $user_id,
            'alternative_email' => 'nullable|email:rfc,filter',
            'phone_number' => 'nullable|string|min:4|max:14',

            'country_id' => 'nullable|integer|exists:countries,id' . $delete_check,
            'gender_id' => 'nullable|integer|exists:genders,id' . $delete_check,
            'title_id' => 'nullable|integer|exists:titles,id' . $delete_check,
            'educational_level_id' => 'nullable|integer|exists:educational_levels,id' . $delete_check,
            'educational_degree_id' => 'nullable|integer|exists:educational_degrees,id' . $delete_check,

            'address' => 'nullable|string|max:255',
        ];
    }


    public function attributes()
    {
        return [
            'first_name' => 'الاسم الاول',
            'family_name' => 'اسم العائلة',

            'email' => 'البريد الالكتروني',
            'alternative_email' => 'البريد الالكتروني البديل',
            'is_active' => 'التفعيل',
            'phone_number' => 'رقم الموبايل',

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
}
