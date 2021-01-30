<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\CMS\PhotoRequest;
use Modules\Sections\Services\CMS\PhotosService;
use Throwable;

class PhotosController extends Controller
{
    private $service;

    public function __construct(PhotosService $service)
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
     * @param PhotoRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(PhotoRequest $request)
    {
        return $this->service->store($request);
    }

    /**
     * Show the specified resource.
     * @param PhotoRequest $request
     * @return JsonResponse|void
     */
    public function show(PhotoRequest $request)
    {
        return $this->service->show($request->photo);
    }

    /**
     * Update the specified resource in storage.
     * @param PhotoRequest $request
     * @return JsonResponse|void
     */
    public function update(PhotoRequest $request)
    {
        return $this->service->update($request->photo, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param PhotoRequest $request
     * @return JsonResponse|void
     */
    public function destroy(PhotoRequest $request)
    {
        return $this->service->delete($request->photo);
    }
}
