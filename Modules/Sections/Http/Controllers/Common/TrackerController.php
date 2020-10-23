<?php

namespace Modules\Sections\Http\Controllers\Common;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Sections\Services\Common\TrackerService;

class TrackerController extends Controller
{

    private $trackerService;

    public function __construct(TrackerService $trackerService)
    {
        $this->trackerService = $trackerService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->trackerService->index();
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse|void
     */
    public function store()
    {
        return $this->trackerService->store();
    }

}
