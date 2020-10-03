<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\PaymentEntryLogs;
use Modules\WareHouse\Entities\PaymentEntryType;

class PaymentEntryLogsRepository extends LaravelRepositoryClass
{
    public function __construct(PaymentEntryLogs $payment_entry_log)
    {
        $this->model = $payment_entry_log;
        $this->cache_key = 'payment_entry_log';
    }
}
