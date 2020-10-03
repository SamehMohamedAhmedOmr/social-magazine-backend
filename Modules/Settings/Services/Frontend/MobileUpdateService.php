<?php

namespace Modules\Settings\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Settings\Repositories\MobileUpdateRepository;
use Modules\Settings\Transformers\MobileUpdateConfigurationResource;
use Modules\Settings\Transformers\MobileUpdateResource;

class MobileUpdateService extends LaravelServiceClass
{
    private $mobile_update_repo;

    public function __construct(MobileUpdateRepository $mobile_update_repo)
    {
        $this->mobile_update_repo = $mobile_update_repo;
    }


    public function configuration($device_os, $app_build_number, $app_version)
    {
        $configuration = [];

        if (!isset($device_os)) {
            $configuration['is_update_request'] = false;
            $configuration['force_update'] = false;
        } else {
            $mobile_update = $this->mobile_update_repo->getLastUpdate($device_os, 'device_type');
            $flag = false;
            if (isset($app_build_number) && isset($mobile_update)) {
                if ($mobile_update->is_active == 1 && $mobile_update->build_number == $app_build_number) {
                    $flag = true;
                }
            } elseif (isset($app_version) && isset($mobile_update)) {
                if ($mobile_update->is_active == 1 && $mobile_update->application_version == $app_version) {
                    $flag = true;
                }
            }

            if (isset($mobile_update) && $flag == true) {
                $configuration['is_update_request'] = true;
                $configuration['force_update'] = ($mobile_update->force_update) ? true: false;
            } else {
                $configuration['is_update_request'] = false;
                $configuration['force_update'] = false;
            }
        }

        $configuration = MobileUpdateConfigurationResource::make($configuration);

        return ApiResponse::format(200, $configuration);
    }
}
