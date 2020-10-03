<?php

namespace Modules\WareHouse\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;
use Modules\WareHouse\Repositories\PurchaseInvoicesRepository;

class PurchaseInvoiceModify implements Rule
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
        $purchase_invoice_repo = App::make(PurchaseInvoicesRepository::class);
        $purchase_invoice = $purchase_invoice_repo->get($value);
        if ($purchase_invoice->status == $this->submitted_status) {
            $this->message_key = 1;
            return false;
        }

        if (count($purchase_invoice->paymentEntry) > 0) {
            $this->message_key = 0;
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
            return 'The :attribute has already Payment Entry.';
        }
        return 'The :attribute is already submitted.';
    }
}
