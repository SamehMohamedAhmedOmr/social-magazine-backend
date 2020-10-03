<?php

namespace Modules\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $return_array = [];

        if ($this->getMethod() == 'POST') { //  ٍStore
            $return_array = [
                'name' => 'bail|required|string|max:254|unique:roles,name',
                'permissions' => 'required|array',
                'permissions.*' => 'required|integer|exists:permissions,id',
            ];
        } elseif ($this->getMethod() == 'GET' || $this->getMethod() == 'DELETE') { // Add - Delete
            $return_array = [
                'role' => 'required|integer|exists:roles,id'
            ];
        } elseif ($this->getMethod() == 'PUT') { // update
            $return_array = [
                'role' => 'required|integer|exists:roles,id',
                'name' => 'bail|required|string|max:254|unique:roles,name,'.request('role'),
                'permissions' => 'array',
                'permissions.*' => 'required|integer|exists:permissions,id',
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
            'name.unique' => 'The name is already exists',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->getMethod() == 'GET' || $this->getMethod() == 'DELETE' || $this->getMethod() == 'PUT') { // Add - Delete - PUT

            // get the query Data
            $query_data = $this->all();

            $query_data['role'] = request('role');

            // replace old input with new input
            $this->replace($query_data);
        }
    }
}
