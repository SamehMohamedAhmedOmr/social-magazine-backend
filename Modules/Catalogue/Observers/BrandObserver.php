<?php

namespace Modules\Catalogue\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Catalogue\Entities\Brand;
use Modules\Settings\Entities\Company;

class BrandObserver
{
    public function creating(Brand $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
