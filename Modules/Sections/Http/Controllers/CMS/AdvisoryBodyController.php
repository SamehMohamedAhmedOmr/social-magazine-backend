<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\CMS\AdvisoryBodyRequest;
use Modules\Sections\Services\CMS\AdvisoryBodyService;
use Throwable;

class AdvisoryBodyController extends Controller
{

    private $service;

    public function __construct(AdvisoryBodyService $service)
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
     * @param AdvisoryBodyRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(AdvisoryBodyRequest $request)
    {
        return $this->service->store($request);
    }

    /**
     * Show the specified resource.
     * @param AdvisoryBodyRequest $request
     * @return JsonResponse|void
     */
    public function show(AdvisoryBodyRequest $request)
    {
        return $this->service->show($request->advisory_body);
    }

    /**
     * Update the specified resource in storage.
     * @param AdvisoryBodyRequest $request
     * @return JsonResponse|void
     */
    public function update(AdvisoryBodyRequest $request)
    {
        return $this->service->update($request->advisory_body, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param AdvisoryBodyRequest $request
     * @return JsonResponse|void
     */
    public function destroy(AdvisoryBodyRequest $request)
    {
        return $this->service->delete($request->advisory_body);
    }

}
