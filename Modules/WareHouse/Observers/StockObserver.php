<?php

namespace Modules\WareHouse\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\WareHouse\Entities\Stock;

class StockObserver
{
    public function creating(Stock $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
