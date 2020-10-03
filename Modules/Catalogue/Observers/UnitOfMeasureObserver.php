<?php

namespace Modules\Catalogue\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Catalogue\Entities\Brand;
use Modules\Catalogue\Entities\Category;
use Modules\Catalogue\Entities\UnitOfMeasure;
use Modules\Settings\Entities\Company;

class UnitOfMeasureObserver
{
    public function creating(UnitOfMeasure $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
