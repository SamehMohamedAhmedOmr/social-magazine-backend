<?php

namespace Modules\WareHouse\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;
use Modules\WareHouse\Repositories\PurchaseReceiptRepository;

class PurchaseReceiptModify implements Rule
{
    protected $message_key;
    private $added_status = 0;
    private $submitted_status = 1;
    private $cancelled_status = 2;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $purchase_receipt_repo = App::make(PurchaseReceiptRepository::class);
        $purchase_receipt = $purchase_receipt_repo->get($value);
        $purchase_invoice = $purchase_receipt->purchaseInvoice;

        if (count($purchase_invoice) > 0) {
            $not_cancelled_invoice = $purchase_invoice->where('status', '<>', $this->cancelled_status);
            if (count($not_cancelled_invoice) > 0) {
                $this->message_key = 0;
                return false;
            }
        }
        if ($purchase_receipt->status == $this->submitted_status) {
            $this->message_key = 1;
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->message_key == 0) {
            return 'The :attribute has already invoice.';
        }
        return 'The :attribute is already submitted.';
    }
}
