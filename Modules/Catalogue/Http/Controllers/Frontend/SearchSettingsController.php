<?php

namespace Modules\Catalogue\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Catalogue\Services\Frontend\SearchSettingService;

class SearchSettingsController extends Controller
{
    private $search_setting_service;

    public function __construct(SearchSettingService $search_setting_service)
    {
        $this->search_setting_service = $search_setting_service;
    }

    public function settings()
    {
        return $this->search_setting_service->settings();
    }
}
