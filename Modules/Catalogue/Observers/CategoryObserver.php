<?php

namespace Modules\Catalogue\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Catalogue\Entities\Brand;
use Modules\Catalogue\Entities\Category;
use Modules\Settings\Entities\Company;

class CategoryObserver
{
    public function creating(Category $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
