<?php

namespace Modules\WareHouse\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;
use Modules\WareHouse\Repositories\PurchaseInvoicesRepository;

class PaymentEntryInvoiceStatus implements Rule
{
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

        if ($purchase_invoice->status != $this->submitted_status) {
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
        return 'This Payment Invoice is not submitted yet';
    }
}
