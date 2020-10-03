<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PriceListRequest extends FormRequest
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
                    'price_list' => 'required|integer|exists:price_lists,id'.$delete_check
                ];
                break;

            case 'POST':
                $rules = [
                    'country_id' => 'integer|exists:countries,id'.$delete_check,
                    'currency_code' => 'required|integer|exists:currency,id',
                    'price_list_name' => 'required|string|max:200',
                    'type' => [
                        'required',
                        // Type shouldn't be duplicated with country
                        Rule::unique('price_lists')->where(function ($query) {
                            $query->where('country_id', request('country_id'));
                        }),
                        // Not exists in assigning deleted price list types
                        Rule::exists('price_list_types', 'id')->where(function ($query) {
                            $query->where('price_list_types.deleted_at', null);
                        }),
                    ], // 'Selling = 0, Buying = 1'
                    'key' => 'required|string|max:255|unique:price_lists,key',
                    'is_special' => 'required|boolean'
                ];
                break;
            case 'PUT':
            case 'PATCH':
                $rules = [
                    'price_list' => 'required|integer|exists:price_lists,id'.$delete_check,

                    'country_id' => 'integer|exists:countries,id'.$delete_check,
                    'currency_code' => 'integer|exists:currency,id',
                    'price_list_name' => 'string|max:200',
                    'type' => [
                        // Type shouldn't be duplicated with country
                        Rule::unique('price_lists')->where(function ($query) {
                            $query->where('country_id', request('country_id'));
                        })->ignore(request('price_list')),
                        // Not exists in assigning deleted price list types
                        Rule::exists('price_list_types', 'id')->where(function ($query) {
                            $query->where('price_list_types.deleted_at', null);
                        }),
                    ], // 'Selling = 0, Buying = 1'
                    'key' => 'string|max:255|unique:price_lists,key,'.request('price_list'),
                    'is_special' => 'boolean',
                    'is_active' => 'boolean'
                ];
                break;
            default:
                $rules = [];
                break;
        }

        return  $rules;
    }


    public function messages()
    {
        return [
            'type.unique' => 'The type has already been taken with this country',
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

    protected function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'price_list');
    }
}
