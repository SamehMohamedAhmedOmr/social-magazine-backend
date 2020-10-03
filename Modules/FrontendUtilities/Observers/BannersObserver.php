<?php

namespace Modules\FrontendUtilities\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\FrontendUtilities\Entities\Banner;

class BannersObserver
{
    public function creating(Banner $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
