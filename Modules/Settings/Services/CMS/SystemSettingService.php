<?php

namespace Modules\Settings\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\SystemSettingRepository;
use Modules\Settings\Transformers\SystemSettingResource;

class SystemSettingService extends LaravelServiceClass
{
    private $system_setting_repo;

    public function __construct(SystemSettingRepository $system_setting_repo)
    {
        $this->system_setting_repo = $system_setting_repo;
    }


    public function store()
    {
        // store system Setting
        $system_setting = $this->storeSystemSetting();

        $system_setting =  SystemSettingResource::make($system_setting);

        return ApiResponse::format(200, $system_setting, 'System Setting added successfully');
    }

    public function show($id)
    {
        $system_setting = $this->system_setting_repo->getFirstRecord();

        if ($system_setting) {
            $system_setting =  SystemSettingResource::make($system_setting);
        }

        return ApiResponse::format(200, $system_setting);
    }

    public function storeSystemSetting()
    {
        $system_setting = $this->system_setting_repo->getFirstRecord();

        $system_setting_id = (isset($system_setting)) ? $system_setting->id : null;

        $optional_array = [];

        if (isset($system_setting_id)) {
            $optional_array['id'] = $system_setting_id;
        }

        return $this->system_setting_repo->updateOrCreate($optional_array, request()->all());
    }
}
