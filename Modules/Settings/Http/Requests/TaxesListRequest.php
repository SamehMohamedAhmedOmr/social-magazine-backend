<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxesListRequest extends FormRequest
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
                    'tax_lists' => 'required|integer|exists:taxes_lists,id'.$delete_check,
                ];
                break;
            case 'POST':
                $active_languages = implode(',', getActiveISO());
                $rules = [
                    'data' => 'required|array',
                    'data.*.lang' => 'required|string|distinct|in:'.$active_languages,
                    'data.*.name' => 'required|string|max:255',

                    'tax_type_id' => 'required|integer|exists:taxes_types,id',

                    'key' => [
                        'required',
                        'string',
                        'max:255',
                        Rule::unique('taxes_lists', 'key')->where(function ($query) {
                            return $query->where('country_id', request('country_id'));
                        })
                    ],
                    'country_id' => 'required|integer|exists:countries,id'.$delete_check,

                    'tax_amount_type_id' => 'required|integer|exists:taxes_amount_types,id',
                    'price' => 'required|numeric|between:0,99999999.9',
                    'is_active' => 'boolean',
                ];
                break;
            case 'PUT':
            case "PATCH":
                $active_languages = implode(',', getActiveISO());
                $rules = [
                    'tax_lists' => 'required|integer|exists:taxes_lists,id'.$delete_check,

                    'data' => 'array',
                    'data.*.lang' => 'required|string|distinct|in:'.$active_languages,
                    'data.*.name' => 'required|string|max:255',

                    'tax_type_id' => 'integer|exists:taxes_types,id',

                    'key' => [
                        'string',
                        'max:255',
                        Rule::unique('taxes_lists', 'key')->where(function ($query) {
                            return $query->where('country_id', request('country_id'));
                        })
                    ],
                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                    'tax_amount_type_id' => 'integer|exists:taxes_amount_types,id',
                    'price' => 'numeric|between:0,99999999.9',
                    'is_active' => 'boolean',
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

    public function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'tax_lists');
    }
}
