<?php

namespace Modules\Settings\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Settings\Entities\Company;

class CompanyObserver
{
    public function creating(Company $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
