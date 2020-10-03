<?php

namespace Modules\WareHouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed district
 */
class DistrictRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $active_languages = implode(',', getActiveISO());
        $delete_check = ',deleted_at,NULL';

        /*
            post ---> store
            patch --> update
            delete --> remove
        */
        if (request()->getMethod() == 'POST') {
            return [
                'data' => 'array|required' ,
                'data.*.lang' => 'string|max:2|distinct|required|in:' . $active_languages,
                'data.*.name' => 'string|max:255|required',
                'data.*.description' => 'string|required',
                'country_id' => 'required|integer|exists:countries,id'.$delete_check ,
                'shipping_role_id' => 'required|integer|exists:shipping_rules,id'.$delete_check,
                'parent_id' => 'integer|exists:districts,id'.$delete_check,
                'is_active' => 'required|boolean',
            ];
        }
        if (request()->getMethod() == 'PATCH' || request()->getMethod() == 'PUT') {
            return [
                'district' => 'required|integer|exists:districts,id'.$delete_check,
                'data' => 'array' ,
                'data.*.lang' => 'string|max:2|distinct|required|in:' . $active_languages,
                'data.*.name' => 'string|max:255|required',
                'data.*.description' => 'string|required',
                'country_id' => 'required|integer|exists:countries,id'.$delete_check ,
                'shipping_role_id' => 'integer|exists:shipping_rules,id'.$delete_check,
                'parent_id' => 'nullable|integer|exists:districts,id'.$delete_check,
                'is_active' => 'boolean',
            ];
        }
        if (request()->getMethod() == 'GET' || request()->getMethod() == 'DELETE') {
            return [
                'district' => 'required|integer|exists:districts,id'.$delete_check
            ];
        }
        return [];
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
        prepareBeforeValidation($this, ['data'], 'district');
    }
}
