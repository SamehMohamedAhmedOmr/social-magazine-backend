<?php

namespace Modules\Settings\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Settings\Entities\ShippingRules;

class ShippingRuleObserver
{
    public function creating(ShippingRules $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
