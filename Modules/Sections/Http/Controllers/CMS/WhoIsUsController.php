<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\CMS\WhoIsUsRequest;
use Modules\Sections\Services\CMS\WhoIsUsService;
use Throwable;

class WhoIsUsController extends Controller
{

    private $whoIsUsService;

    public function __construct(WhoIsUsService $whoIsUsService)
    {
        $this->whoIsUsService = $whoIsUsService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->whoIsUsService->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param WhoIsUsRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(WhoIsUsRequest $request)
    {
        return $this->whoIsUsService->store($request);
    }

    /**
     * Show the specified resource.
     * @param WhoIsUsRequest $request
     * @return JsonResponse|void
     */
    public function show(WhoIsUsRequest $request)
    {
        return $this->whoIsUsService->show($request->who_is_us_section);
    }

    /**
     * Update the specified resource in storage.
     * @param WhoIsUsRequest $request
     * @return JsonResponse|void
     */
    public function update(WhoIsUsRequest $request)
    {
        return $this->whoIsUsService->update($request->who_is_us_section, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param WhoIsUsRequest $request
     * @return JsonResponse|void
     */
    public function destroy(WhoIsUsRequest $request)
    {
        return $this->whoIsUsService->delete($request->who_is_us_section);
    }

}
