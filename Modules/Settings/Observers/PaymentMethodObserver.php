<?php

namespace Modules\Settings\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Settings\Entities\PaymentMethod;

class PaymentMethodObserver
{
    public function creating(PaymentMethod $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
