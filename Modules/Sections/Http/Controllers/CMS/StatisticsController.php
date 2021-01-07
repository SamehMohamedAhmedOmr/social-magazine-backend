<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Sections\Services\CMS\StatisticsService;

class StatisticsController extends Controller
{
    private $service;

    public function __construct(StatisticsService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->service->index();
    }

}
