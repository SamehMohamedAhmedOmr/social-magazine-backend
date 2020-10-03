<?php

namespace Modules\WareHouse\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\WareHouse\Entities\PurchaseInvoice;
use Modules\WareHouse\Entities\PurchaseReceipt;

class PurchaseInvoiceObserver
{
    public function creating(PurchaseInvoice $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
