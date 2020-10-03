<?php

namespace Modules\WareHouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\WareHouse\Rules\PurchaseReceiptModify;

class PurchaseReceiptStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $delete_check = ',deleted_at,NULL';
        return [
            'purchase_receipt' => [
                'bail',
                'required',
                'integer',
                'exists:purchase_receipts,id'.$delete_check,
                new PurchaseReceiptModify,
            ],
            'status' => 'required|in:1,2', // 1 = Submitted, 2 = Cancelled
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

    public function prepareForValidation()
    {
        prepareBeforeValidation($this, [], 'purchase_receipt');
    }
}
