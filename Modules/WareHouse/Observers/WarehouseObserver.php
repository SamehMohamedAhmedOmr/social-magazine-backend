<?php

namespace Modules\WareHouse\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\WareHouse\Entities\Warehouse;

class WarehouseObserver
{
    public function creating(Warehouse $object)
    {
        $object->country_id = ($object->country_id) ? $object->country_id : UtilitiesHelper::getCountryId();
    }
}
