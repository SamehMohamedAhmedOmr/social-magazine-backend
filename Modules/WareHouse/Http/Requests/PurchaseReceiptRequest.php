<?php

namespace Modules\WareHouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\WareHouse\Rules\PurchaseReceiptModify;

class PurchaseReceiptRequest extends FormRequest
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
                    'purchase_receipt' => 'required|integer|exists:purchase_receipts,id'.$delete_check,
                ];
                break;
            case 'DELETE':
                $rules = [
                    'purchase_receipt' => [
                        'bail',
                        'required',
                        'integer',
                        'exists:purchase_receipts,id'.$delete_check,
                        new PurchaseReceiptModify,
                    ]
                ];
                break;
            case 'POST':
                $rules = [
                    'purchase_order_id' => 'required|integer|exists:purchase_orders,id'.$delete_check,
                    'company_id' => 'required|integer|exists:companies,id'.$delete_check,
                    'shipping_rule_id' => 'required|integer|exists:shipping_rules,id'.$delete_check,
                    'tax_id' => 'required|integer|exists:taxes_lists,id'.$delete_check,

                    'products' => 'required|array',
                    'products.*.product_id' => 'required|integer|distinct', // TODO => check exist in product table
                    'products.*.accepted_quantity' => 'required|integer|min:1|max:99999999999999999999',

                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];
                break;
            case 'PUT':
            case "PATCH":
                $rules = [
                    'purchase_receipt' => [
                        'bail',
                        'required',
                        'integer',
                        'exists:purchase_receipts,id'.$delete_check,
                        new PurchaseReceiptModify
                    ],

                    'purchase_order_id' => 'integer|exists:purchase_orders,id'.$delete_check,
                    'company_id' => 'integer|exists:companies,id'.$delete_check,
                    'shipping_rule_id' => 'integer|exists:shipping_rules,id'.$delete_check,
                    'tax_id' => 'integer|exists:taxes_lists,id'.$delete_check,

                    'products' => 'array',
                    'products.*.product_id' => 'required|integer|distinct', // TODO => check exist in product table
                    'products.*.accepted_quantity' => 'required|integer|min:1|max:99999999999999999999',

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

    public function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'purchase_receipt');
    }
}
