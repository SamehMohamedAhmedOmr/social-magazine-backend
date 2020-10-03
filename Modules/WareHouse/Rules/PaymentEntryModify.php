<?php

namespace Modules\WareHouse\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;
use Modules\WareHouse\Repositories\PaymentEntryRepository;

class PaymentEntryModify implements Rule
{
    private $submitted_status = 1;

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
        $payment_entry_repo = App::make(PaymentEntryRepository::class);
        $payment_entry = $payment_entry_repo->get($value);

        if ($payment_entry->status == $this->submitted_status) {
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
        return 'This Payment Entry has already submitted';
    }
}
