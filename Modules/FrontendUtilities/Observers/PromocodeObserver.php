<?php

namespace Modules\FrontendUtilities\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\FrontendUtilities\Entities\Promocode;

class PromocodeObserver
{
    public function creating(Promocode $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
