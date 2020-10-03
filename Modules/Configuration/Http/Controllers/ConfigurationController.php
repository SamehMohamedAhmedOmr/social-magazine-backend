<?php

namespace Modules\Configuration\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Configuration\Services\CMS\ProjectInformationService;

class ConfigurationController extends Controller
{

    private $projectInformationService;
    public function __construct(ProjectInformationService $projectInformationService){
        $this->projectInformationService = $projectInformationService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function projectInformation()
    {
        return $this->projectInformationService->projectInformation();
    }


}
