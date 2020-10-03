<?php

namespace Modules\Settings\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Settings\Entities\SystemNote;
use Modules\Settings\Entities\SystemSetting;

class SystemNotesObserver
{
    public function creating(SystemNote $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
