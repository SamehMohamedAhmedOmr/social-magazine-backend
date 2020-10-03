<?php

namespace Modules\WareHouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WareshouseRequest extends FormRequest
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
                $rules = [
                    'warehouse' => 'required|integer|exists:warehouses,id'.$delete_check
                ];
                break;
            case 'POST':
                $active_languages = implode(',', getActiveISO());
                $rules = [
                    // warehouse table
                    'warehouse_code' => 'required|string|unique:warehouses,warehouse_code|max:255',
                    'default_warehouse' => [
                        'boolean',
                        Rule::unique('warehouses', 'default_warehouse')->where(function ($query) {
                            return $query->where('default_warehouse', 1)->where('deleted_at', null);
                        })
                    ],
                    // warehouse-language table
                    'data' => 'required|array',
                    'data.*.lang' => 'required|string|in:'.$active_languages,
                    'data.*.name' => 'required|string|max:150',
                    'data.*.description' => 'nullable|string',

                    'all' => 'required_without:districts|boolean',
                    'districts' => 'required_without:all|array',
                    'districts.*' => 'required|integer|exists:districts,id'.$delete_check,
                ];
                break;
            case 'PUT':
            case 'PATCH':
                $active_languages = implode(',', getActiveISO());
                $rules = [
                    // target ID
                    'warehouse' => 'required|integer|exists:warehouses,id'.$delete_check,
                    // warehouse table
                    'warehouse_code' => [
                        'string',
                        'max:255',
                        Rule::unique('warehouses', 'warehouse_code')->ignore(request('warehouse'))
                    ],
                    'is_active' => 'boolean',
                    'default_warehouse' => [
                        'boolean',
                        Rule::unique('warehouses', 'default_warehouse')->where(function ($query) {
                            return $query->where('default_warehouse', 1)->where('deleted_at', null);
                        })->ignore(request('warehouse'))
                    ],
                    // warehouse-language table
                    'data' => 'array',
                    'data.*.lang' => 'required|string|in:'.$active_languages,
                    'data.*.name' => 'required|string|max:150',
                    'data.*.description' => 'nullable|string',

                    'all' => 'boolean',
                    'districts' => 'array',
                    'districts.*' => 'required|integer|exists:districts,id'.$delete_check,
                ];
                break;
            default:
                $rules = [];
                break;
        }

        return $rules;
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
        if ($this->getMethod() == 'GET' || $this->getMethod() == 'DELETE' || $this->getMethod() == 'PUT') { // Add - Delete
            // get the query Data
            $query_data = $this->all();

            $query_data['warehouse'] = request('warehouse');

            // replace old input with new input
            $this->replace($query_data);
        }
    }
}
