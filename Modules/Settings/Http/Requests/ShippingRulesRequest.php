<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingRulesRequest extends FormRequest
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
                    'shipping_rule' => 'required|integer|exists:shipping_rules,id'.$delete_check
                ];
                break;
            case 'POST':
                $rules = [
                    'shipping_rule_label' => 'required|string|max:255',
                    'key' => 'required|string|max:255|unique:shipping_rules,key',
                    'price' => 'required|numeric|between:0,99999999.99',
                    'is_active' => 'boolean',
                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];
                break;
            case 'PATCH':
            case 'PUT':
                $rules = [
                    'shipping_rule' => 'required|integer|exists:shipping_rules,id'.$delete_check,

                    'shipping_rule_label' => 'string|max:255',
                    'key' => 'string|max:255|unique:shipping_rules,key,'.request('shipping_rule'),
                    'price' => 'numeric|between:0,99999999.99',
                    'is_active' => 'boolean',
                    'country_id' => 'integer|exists:countries,id'.$delete_check,

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
        prepareBeforeValidation($this, [], 'shipping_rule');
    }
}
