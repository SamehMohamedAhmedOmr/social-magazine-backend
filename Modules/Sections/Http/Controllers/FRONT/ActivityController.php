<?php

namespace Modules\Sections\Http\Controllers\FRONT;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\FRONT\ActivityRequest;
use Modules\Sections\Services\Frontend\ActivitiesService;

class ActivityController extends Controller
{

    private $service;

    public function __construct(ActivitiesService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->service->index();
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function latest()
    {
        return $this->service->latest();
    }

    /**
     * Display a listing of the resource.
     * @param ActivityRequest $request
     * @return JsonResponse|void
     */
    public function get(ActivityRequest $request)
    {
        return $this->service->show($request->slug);
    }

}
