<?php

namespace Modules\WareHouse\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\WareHouse\Entities\PurchaseOrder;

class PurchaseOrderObserver
{
    public function creating(PurchaseOrder $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
