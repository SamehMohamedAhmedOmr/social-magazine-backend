<?php

namespace Modules\Sections\Http\Controllers\FRONT;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Sections\Services\Frontend\WhoIsUsService;

class WhoIsUsController extends Controller
{
    private $whoIsUsService;

    public function __construct(WhoIsUsService $whoIsUsService)
    {
        $this->whoIsUsService = $whoIsUsService;
    }


    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->whoIsUsService->index();
    }


}
