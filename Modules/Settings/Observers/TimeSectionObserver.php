<?php

namespace Modules\Settings\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Settings\Entities\TimeSection;

class TimeSectionObserver
{
    public function creating(TimeSection $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
