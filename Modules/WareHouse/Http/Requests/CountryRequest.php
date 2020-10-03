<?php

namespace Modules\WareHouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $active_languages = implode(',', getActiveISO());
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
                'country_code' => 'required|max:5|string|unique:countries,country_code' ,
                // Image multi/part validation
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|required',
                'is_active' => 'required|boolean',
            ];
        }
        if (request()->getMethod() == 'PATCH' || request()->getMethod() == 'PUT') {
            return [
                'country' => 'required|integer|exists:countries,id,deleted_at,NULL',
                'data' => 'array' ,
                'data.*.lang' => 'string|max:2|distinct|required|in:' . $active_languages,
                'data.*.name' => 'string|max:255|required',
                'data.*.description' => 'string|required',
                'country_code' => 'string|max:5|unique:countries,country_code,'. request('country') ,
                // Image multi/part validation
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'is_active' => 'boolean',
            ];
        }
        if (request()->getMethod() == 'GET' || request()->getMethod() == 'DELETE') {
            return [
                'country' => 'required|integer|exists:countries,id,deleted_at,NULL'
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
        prepareBeforeValidation($this, ['data'], 'country');
    }
}
