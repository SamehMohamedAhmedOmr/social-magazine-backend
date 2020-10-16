<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
                    'admin' => [
                        'required',
                        'integer',
                        Rule::exists('users', 'id')->where(function ($query) {
                            $query->where('deleted_at', null)->where('user_type', $this->admin_type);
                        }),
                    ],
                ];
                break;
            case 'POST':
                $default = [
                    // Required Address Info
                    'name' => 'required|string|regex:'.UsersErrorsHelper::regexName().'|max:255',
                    'email' => 'required|email:rfc,filter|unique:users,email',
                    'password' => 'required|string|min:6',


                    'roles' => 'required|array',
                    'roles.*' => 'required|integer|exists:roles,id'.$delete_check,
                ];
                break;
            case 'PUT':
                $default = [
                    'first_name' => 'nullable|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',
                    'family_name' => 'nullable|string|regex:' . UsersErrorsHelper::regexName() . '|max:255',

                    'email' => 'nullable|email:rfc,filter|unique:users,email,' . $user_id,
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
        prepareBeforeValidation($this, [], 'admin');
    }
}
