<?php

namespace Modules\FrontendUtilities\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\FrontendUtilities\Entities\Collection;

class CollectionObserver
{
    public function creating(Collection $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
