<?php

namespace Modules\Settings\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Settings\Http\Requests\MobileUpdateConfigurationRequest;
use Modules\Settings\Services\Frontend\MobileUpdateService;

class MobileUpdateController extends Controller
{
    private $mobile_update_service;

    public function __construct(MobileUpdateService $mobile_update_service)
    {
        $this->mobile_update_service = $mobile_update_service;
    }

    public function configuration(MobileUpdateConfigurationRequest $request)
    {
        return $this->mobile_update_service->configuration($request->device_os, $request->app_build_number, $request->app_version);
    }
}
