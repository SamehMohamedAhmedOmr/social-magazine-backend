<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\PaymentEntryType;

class PaymentEntryTypeRepository extends LaravelRepositoryClass
{
    public function __construct(PaymentEntryType $payment_entry_type)
    {
        $this->model = $payment_entry_type;
        $this->cache_key = 'payment_entry_type';
    }
}
