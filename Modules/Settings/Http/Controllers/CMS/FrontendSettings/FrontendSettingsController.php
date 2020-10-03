<?php

namespace Modules\Settings\Http\Controllers\CMS\FrontendSettings;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Settings\Http\Requests\FrontendSettings\FrontendSettingsRequest;
use Modules\Settings\Services\CMS\FrontendSettingsService;

class FrontendSettingsController extends Controller
{

    private $frontend_settings_service;

    public function __construct(FrontendSettingsService $frontend_settings_service)
    {
        $this->frontend_settings_service = $frontend_settings_service;
    }

    /**
     * Store a newly created resource in storage.
     * @param FrontendSettingsRequest $request
     * @return void
     */
    public function store(FrontendSettingsRequest $request)
    {
        return $this->frontend_settings_service->store($request);
    }

    /**
     * Show the specified resource.
     * @return JsonResponse|void
     */
    public function show()
    {
        return $this->frontend_settings_service->show();
    }

}
