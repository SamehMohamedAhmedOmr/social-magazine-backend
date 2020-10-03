<?php

namespace Modules\WareHouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\WareHouse\Rules\PaymentEntryInvoicePrice;
use Modules\WareHouse\Rules\PaymentEntryInvoiceStatus;
use Modules\WareHouse\Rules\PaymentEntryModify;

class PaymentEntryRequest extends FormRequest
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
                    'payment_entry' => 'required|integer|exists:payment_entries,id'.$delete_check,
                ];
                break;

            case 'DELETE':
                $rules = [
                    'payment_entry' => [
                        'bail',
                        'required',
                        'integer',
                        'exists:payment_entries,id'.$delete_check,
                        new PaymentEntryModify,
                    ],
                ];
                break;

            case 'POST':
                $rules = [
                    'purchase_invoice_id' => [
                        'bail',
                        'required',
                        'integer',
                        'exists:purchase_invoices,id'.$delete_check,
                        new PaymentEntryInvoiceStatus,
                    ],
                    'payment_entry_type' => 'required|string|exists:payment_entry_types,key',
                    'payment_price' => [
                        'required',
                        'numeric',
                        'between:0,99999999.99',
                        new PaymentEntryInvoicePrice
                    ],
                    'payment_reference' => 'required_if:payment_entry_type,CASH|string|max:255', // cash
                    'country_id' => 'integer|exists:countries,id'.$delete_check,

                ];
                break;
            case 'PUT':
            case "PATCH":
                $rules = [
                    'payment_entry' => [
                        'bail',
                        'required',
                        'integer',
                        'exists:payment_entries,id'.$delete_check,
                        new PaymentEntryModify,
                    ],

                    'purchase_invoice_id' => [
                        'bail',
                        'required',
                        'integer',
                        'exists:purchase_invoices,id'.$delete_check,
                        new PaymentEntryInvoiceStatus,
                    ],
                    'payment_entry_type' => 'string|exists:payment_entry_types,key',
                    'status' => 'in:1,2', // 1 = Submitted, 2 = Cancelled

                    'payment_price' => [
                        'numeric',
                        'between:0,99999999.99',
                        new PaymentEntryInvoicePrice(request('payment_entry'))
                    ],

                    'payment_reference' => 'required_if:payment_entry_type,CASH|string|max:255', // cash

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
        prepareBeforeValidation($this, [], 'payment_entry');
    }
}
