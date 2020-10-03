<?php

namespace Modules\WareHouse\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\WareHouse\Entities\PaymentEntry;

class PaymentEntryObserver
{
    public function creating(PaymentEntry $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
