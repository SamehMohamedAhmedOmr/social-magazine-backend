<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Users\Facades\UsersErrorsHelper;

class AdminRequest extends FormRequest
{
    protected $admin_type = 1;

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

                    'countries' => 'required|array',
                    'countries.*' => 'required|integer|exists:countries,id'.$delete_check,

                    'warehouses' => 'required|array',
                    'warehouses.*' => 'required|integer|exists:countries,id'.$delete_check,

                    'roles' => 'required|array',
                    'roles.*' => 'required|integer|exists:roles,id'.$delete_check,
                ];
                break;
            case 'PUT':
                $default = [
                    'admin' => [
                        'required',
                        'integer',
                        Rule::exists('users', 'id')->where(function ($query) {
                            $query->where('deleted_at', null)->where('user_type', $this->admin_type);
                        }),
                    ],
                    'name' => 'string|regex:'.UsersErrorsHelper::regexName().'|max:255',
                    'email' => [
                        'email:rfc,filter',
                        Rule::unique('users', 'email')->ignore($this->admin)
                    ],

                    'is_active' => 'boolean',

                    'countries' => 'array',
                    'countries.*' => 'required|integer|exists:countries,id'.$delete_check,

                    'warehouses' => 'array',
                    'warehouses.*' => 'required|integer|exists:countries,id'.$delete_check,

                    'roles' => 'array',
                    'roles.*' => 'required|integer|exists:roles,id'.$delete_check,
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
