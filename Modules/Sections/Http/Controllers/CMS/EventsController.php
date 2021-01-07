<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\CMS\EventsRequest;
use Modules\Sections\Services\CMS\EventsService;
use Throwable;

class EventsController extends Controller
{
    private $service;

    public function __construct(EventsService $service)
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
     * @param EventsRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(EventsRequest $request)
    {
        return $this->service->store($request);
    }

    /**
     * Show the specified resource.
     * @param EventsRequest $request
     * @return JsonResponse|void
     */
    public function show(EventsRequest $request)
    {
        return $this->service->show($request->event);
    }

    /**
     * Update the specified resource in storage.
     * @param EventsRequest $request
     * @return JsonResponse|void
     */
    public function update(EventsRequest $request)
    {
        return $this->service->update($request->event, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param EventsRequest $request
     * @return JsonResponse|void
     */
    public function destroy(EventsRequest $request)
    {
        return $this->service->delete($request->event);
    }
}
