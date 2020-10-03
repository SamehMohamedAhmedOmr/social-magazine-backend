<?php

namespace Modules\FacebookCatalogue\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\FacebookCatalogue\Services\FacebookCatalogueSettingService;
use Modules\FacebookCatalogue\Http\Requests\FacebookCatalogueSettingRequest;

class FacebookCatalogueSettingsController extends Controller
{
    private $facebookCatalogueSettingService;

    public function __construct(FacebookCatalogueSettingService $facebookCatalogueSettingService)
    {
        $this->facebookCatalogueSettingService = $facebookCatalogueSettingService;
    }

    public function show()
    {
        return $this->facebookCatalogueSettingService->show();
    }

    public function updateOrCreate(FacebookCatalogueSettingRequest $facebookCatalogueSettingRequest)
    {
        return $this->facebookCatalogueSettingService->updateOrCreate($facebookCatalogueSettingRequest);
    }
}
