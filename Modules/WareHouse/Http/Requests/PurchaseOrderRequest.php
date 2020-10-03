<?php

namespace Modules\WareHouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\WareHouse\Rules\PurchaseOrderModify;

class PurchaseOrderRequest extends FormRequest
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
                $rules = [
                    'purchase_order' => 'required|integer|exists:purchase_orders,id'.$delete_check,
                    'with_price' => 'boolean',
                ];
                break;
            case 'DELETE':
                $rules = [
                    'purchase_order' => [
                        'bail',
                        'required',
                        'integer',
                        'exists:purchase_orders,id'.$delete_check,
                        new PurchaseOrderModify()
                    ]
                ];
                break;
            case 'POST':
                $rules = [
                    'delivery_date' => 'required|date_format:Y-m-d',
                    'discount_type' => 'required|boolean', // 0 is Fixed , 1 is percentage

                    'warehouse_id' => 'required|integer|exists:warehouses,id'.$delete_check,
                    'company_id' => 'required|integer|exists:companies,id'.$delete_check,
                    'shipping_rule_id' => 'integer|exists:shipping_rules,id'.$delete_check,
                    'tax_id' => 'integer|exists:taxes_lists,id'.$delete_check,
                    'is_active' => 'required|boolean',

                    'products' => 'required|array',
                    'products.*.product_id' => 'required|integer|distinct', // TODO => check exist in product table
                    'products.*.quantity' => 'required|integer|max:99999999999999999999',
                    'products.*.price' => 'required|numeric|between:0,99999999.99',
                    'products.*.total_amount' => 'required|integer|max:2147483647',

                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];

                $rules = array_merge($rules, $this->discountValidation());
                break;
            case 'PUT':
            case "PATCH":
                $rules = [
                    'purchase_order' => [
                        'bail',
                        'required',
                        'integer',
                        'exists:purchase_orders,id'.$delete_check,
                        new PurchaseOrderModify()
                    ],

                    'delivery_date' => 'date_format:Y-m-d',
                    'discount_type' => 'boolean', // 0 is Fixed , 1 is percentage

                    'warehouse_id' => 'integer|exists:warehouses,id'.$delete_check,
                    'company_id' => 'integer|exists:companies,id'.$delete_check,
                    'shipping_rule_id' => 'integer|exists:shipping_rules,id'.$delete_check,
                    'tax_id' => 'integer|exists:taxes_lists,id'.$delete_check,
                    'is_active' => 'boolean',

                    'products' => 'array',
                    'products.*.product_id' => 'integer|distinct', // TODO => check exist in product table
                    'products.*.quantity' => 'required|integer|max:99999999999999999999',
                    'products.*.price' => 'required|numeric|between:0,99999999.99',
                    'products.*.total_amount' => 'required|integer|max:2147483647',

                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];

                $rules = array_merge($rules, $this->discountValidation(1));
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
        prepareBeforeValidation($this, [], 'purchase_order');
    }

    private function discountValidation($type = 0)
    {
        if (request('discount_type')) { // 1 is Percentage
            if ($type == 0) { // mean POST TO ADD REQUEST
                $discount_validation = ['discount' => 'required|numeric|between:0,99.99'];
            } else {
                $discount_validation = ['discount' => 'required_with:discount_type|numeric|between:0,99.99'];
            }
        } else { // 0 => fixed
           if ($type == 0) { // mean POST TO ADD REQUEST
               $discount_validation = ['discount' => 'required|numeric|between:0,99999999.99'];
           } else {
               $discount_validation = ['discount' => 'required_with:discount_type|numeric|between:0,99999999.99'];
           }
        }
        return $discount_validation;
    }
}
