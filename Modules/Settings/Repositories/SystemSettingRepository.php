<?php

namespace Modules\Settings\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\SystemSetting;

class SystemSettingRepository extends LaravelRepositoryClass
{
    public function __construct(SystemSetting $system_setting)
    {
        $this->model = $system_setting;
        $this->cache_key = 'system_setting';
    }

    public function getFirstRecord()
    {
        return $this->model->first();
    }

    public function updateOrCreate($optional_array, $required_array)
    {
        return $this->model->updateOrCreate($optional_array, $required_array);
    }
}
