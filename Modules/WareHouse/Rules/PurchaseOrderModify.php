<?php

namespace Modules\WareHouse\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;
use Modules\WareHouse\Entities\PurchaseOrder;
use Modules\WareHouse\Repositories\PurchaseOrderRepository;

class PurchaseOrderModify implements Rule
{
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
        $purchase_order_repo = App::make(PurchaseOrderRepository::class);
        $purchase_order = $purchase_order_repo->get($value);
        $purchase_receipts = $purchase_order->PurchaseReceipt;
        if (count($purchase_receipts) > 0) {
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
        return 'Cannot updated this purchase order due to It has already receipts';
    }
}
