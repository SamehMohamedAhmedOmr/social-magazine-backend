<?php

namespace Modules\WareHouse\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;
use Modules\WareHouse\Repositories\PaymentEntryRepository;
use Modules\WareHouse\Repositories\PurchaseInvoicesRepository;

class PaymentEntryInvoicePrice implements Rule
{
    protected $except_id;
    protected $purchase_invoice_repo;

    /**
     * Create a new rule instance.
     *
     * @param $except_id
     */
    public function __construct($except_id = null)
    {
        $this->except_id = $except_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param $payment_price
     * @return bool
     */
    public function passes($attribute, $payment_price)
    {
        if (request()->has('purchase_invoice_id')) {
            $purchase_invoice_repo = App::make(PurchaseInvoicesRepository::class);
            $payment_entry_repo = App::make(PaymentEntryRepository::class);

            $purchase_invoice = $purchase_invoice_repo->get(request('purchase_invoice_id'));

            $payments_entries = $payment_entry_repo->all(['purchase_invoice_id' => $purchase_invoice->id]);

            $total_price = 0;
            foreach ($payments_entries as $payments_entry) {
                if ($payments_entry->id == $this->except_id) {
                    continue;
                }
                $total_price += $payments_entry->payment_price;
            }

            $total_price += $payment_price;

            if ($total_price > $purchase_invoice->total_price) {
                return false;
            }
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
        return 'Total payment price of all payment entries is larger than Purchase invoice Total Price';
    }
}
