<?php

namespace Modules\Settings\Observers;

use Modules\Base\Facade\UtilitiesHelper;
use Modules\Settings\Entities\SystemSetting;

class SystemSettingObserver
{
    public function creating(SystemSetting $object)
    {
        $object->country_id = UtilitiesHelper::getCountryId();
    }
}
