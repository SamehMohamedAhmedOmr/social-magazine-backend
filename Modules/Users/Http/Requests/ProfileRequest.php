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
            'first_name' => 'string|regex:'.UsersErrorsHelper::regexName().'|max:255',
            'family_name' => 'required|string|regex:'.UsersErrorsHelper::regexName().'|max:255',

            'email' => 'email:rfc,filter|unique:users,email,'.$user_id,
            'alternative_email' => 'email:rfc,filter',
            'is_active' => 'boolean',
            'password' => 'string|min:6',
            'phone_number' => 'string|min:4|max:14',

            'country_id' => 'integer|exists:countries,id'.$delete_check,
            'gender_id' => 'integer|exists:genders,id'.$delete_check,
            'title_id' => 'integer|exists:titles,id'.$delete_check,
            'educational_level_id' => 'integer|exists:educational_levels,id'.$delete_check,
            'educational_degree_id' => 'integer|exists:educational_degrees,id'.$delete_check,

            'educational_field' => 'string|max:255',
            'university' => 'string|max:255',
            'faculty' => 'string|max:255',
            'fax_number' => 'string|max:255',
            'address' => 'string|max:255',
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
