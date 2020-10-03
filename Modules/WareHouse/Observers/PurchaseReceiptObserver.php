<?php

namespace Modules\WareHouse\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\WareHouse\Entities\PurchaseReceipt;

class PurchaseReceiptObserver
{
    public function creating(PurchaseReceipt $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
