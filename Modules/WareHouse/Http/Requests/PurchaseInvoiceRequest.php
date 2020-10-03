<?php

namespace Modules\WareHouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\WareHouse\Rules\PurchaseInvoiceModify;

class PurchaseInvoiceRequest extends FormRequest
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
                    'purchase_invoice' => 'required|integer|exists:purchase_invoices,id'.$delete_check,
                ];
                break;
            case 'DELETE':
                $rules = [
                    'purchase_invoice' => [
                        'bail',
                        'required',
                        'integer',
                        'exists:purchase_invoices,id'.$delete_check,
                        new PurchaseInvoiceModify(),
                    ],
                ];
                break;
            case 'POST':
                $rules = [
                    'purchase_receipt_id' => [
                        'required',
                        'exists:purchase_receipts,id'.$delete_check,
                        Rule::unique('purchase_invoices', 'purchase_receipt_id')->where(function ($query) {
                            return $query->where('deleted_At', null);
                        })
                    ],
                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];
                break;
            case 'PUT':
            case "PATCH":
                $rules = [
                    'purchase_invoice' => [
                        'bail',
                        'required',
                        'integer',
                        'exists:purchase_invoices,id'.$delete_check,
                        new PurchaseInvoiceModify(),
                    ],

                    'purchase_receipt_id' => [
                        'exists:purchase_receipts,id'.$delete_check,
                        Rule::unique('purchase_invoices', 'purchase_receipt_id')->where(function ($query) {
                            return $query->where('deleted_At', null);
                        })->ignore(request('purchase_invoice'))
                    ],
                    'status' => 'required|in:1,2', // 1 = Submitted, 2 = Cancelled
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
        prepareBeforeValidation($this, [], 'purchase_invoice');
    }
}
