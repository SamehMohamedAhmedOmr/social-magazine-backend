<?php

namespace Modules\Settings\Http\Controllers\Frontend\FrontendSettings;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Settings\Services\Frontend\FrontendSettingsService;

class FrontendSettingsController extends Controller
{

    private $frontend_settings_service;

    public function __construct(FrontendSettingsService $frontend_settings_service)
    {
        $this->frontend_settings_service = $frontend_settings_service;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function show()
    {
        return $this->frontend_settings_service->show();
    }

}
