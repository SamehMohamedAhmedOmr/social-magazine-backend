<?php

namespace Modules\Settings\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Settings\Entities\Currency;

class CurrencyObserver
{
    public function creating(Currency $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
