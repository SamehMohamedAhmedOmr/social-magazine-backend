<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Settings\Http\Requests\SystemSettingRequest;
use Modules\Settings\Services\CMS\SystemSettingService;

class SystemSettingController extends Controller
{
    private $system_setting_service;
    public function __construct(SystemSettingService $system_setting_service)
    {
        $this->system_setting_service = $system_setting_service;
    }

    /**
     * Store a newly created resource in storage.
     * @param SystemSettingRequest $request
     * @return void
     */
    public function store(SystemSettingRequest $request)
    {
        return $this->system_setting_service->store();
    }

    /**
     * Show the specified resource.
     * @return JsonResponse|void
     */
    public function show()
    {
        return $this->system_setting_service->show(null);
    }
}
