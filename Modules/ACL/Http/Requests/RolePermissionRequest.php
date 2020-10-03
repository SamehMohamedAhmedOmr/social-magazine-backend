<?php

namespace Modules\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $return_array = [];
        if ($this->getMethod() == 'PUT') { //  ٍAssign
            $return_array = [
                'permissions' => 'required|array',
                'permissions.*' => 'required|integer|exists:permissions,id',
                'role_key' => 'required|string|exists:roles,key'
            ];
        } elseif ($this->getMethod() == 'POST') { // Add Role with Permission
            $return_array = [
                'name' => 'bail|required|string|max:254',
                'key' => 'string|max:254|unique:roles,key',
                "permissions" => "required|array|min:1",
                'permissions.*' => 'required|distinct|exists:permissions,id'
            ];
        }
        return $return_array;
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

    public function messages()
    {
        return [
            'key.unique' => 'The name is already exists',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->getMethod() == 'POST') {
            $key = str_replace(' ', '_', strtoupper(request('name')));
            $query_data = $this->all();

            $query_data['key'] = $key;

            // replace old input with new input
            $this->replace($query_data);
        }
    }
}
