<?php

namespace Modules\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() // Assign - revoke User Role
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'role_key' => 'required|string|exists:roles,key'
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
