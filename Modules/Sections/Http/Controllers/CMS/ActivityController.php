<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\CMS\ActivitiesRequest;
use Modules\Sections\Services\CMS\ActivitiesService;
use Throwable;

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
     * Store a newly created resource in storage.
     * @param ActivitiesRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ActivitiesRequest $request)
    {
        return $this->service->store($request);
    }

    /**
     * Show the specified resource.
     * @param ActivitiesRequest $request
     * @return JsonResponse|void
     */
    public function show(ActivitiesRequest $request)
    {
        return $this->service->show($request->magazine_news);
    }

    /**
     * Update the specified resource in storage.
     * @param ActivitiesRequest $request
     * @return JsonResponse|void
     */
    public function update(ActivitiesRequest $request)
    {
        return $this->service->update($request->magazine_news, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param ActivitiesRequest $request
     * @return JsonResponse|void
     */
    public function destroy(ActivitiesRequest $request)
    {
        return $this->service->delete($request->magazine_news);
    }
}
