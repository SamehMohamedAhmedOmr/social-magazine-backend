<?php

namespace Modules\FacebookCatalogue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FacebookCatalogue\Services\FacebookCatalogueLogService;
use Modules\FacebookCatalogue\Http\Requests\FacebookCatalogueSettingRequest;

class FacebookCatalogueLogsController extends Controller
{
    private $facebookCatalogueLogService;

    public function __construct(FacebookCatalogueLogService $facebookCatalogueLogService)
    {
        $this->facebookCatalogueLogService = $facebookCatalogueLogService;
    }

    public function paginate()
    {
        return $this->facebookCatalogueLogService->pagination();
    }

    public function delete(Request $request)
    {
        return $this->facebookCatalogueLogService->delete($request->log_id);
    }
}
