<?php

namespace Modules\FrontendUtilities\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\FrontendUtilities\Http\Requests\PromocodeRequest;
use Modules\FrontendUtilities\Services\CMS\PromocodeService;

class PromocodeController extends Controller
{
    private $promocode_service;

    public function __construct(PromocodeService $promocode_service)
    {
        $this->promocode_service = $promocode_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->promocode_service->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param PromocodeRequest $request
     * @return JsonResponse
     */
    public function store(PromocodeRequest $request)
    {
        return $this->promocode_service->store();
    }

    /**
     * Show the specified resource.
     * @param PromocodeRequest $request
     * @return JsonResponse
     */
    public function show(PromocodeRequest $request)
    {
        return $this->promocode_service->show($request->promocode);
    }


    /**
     * Update the specified resource in storage.
     * @param PromocodeRequest $request
     * @return JsonResponse
     */
    public function update(PromocodeRequest $request)
    {
        return $this->promocode_service->update($request->promocode);
    }

    /**
     * Remove the specified resource from storage.
     * @param PromocodeRequest $request
     * @return JsonResponse
     */
    public function destroy(PromocodeRequest $request)
    {
        return $this->promocode_service->delete($request->promocode);
    }

    public function export(PaginationRequest $request)
    {
        return $this->promocode_service->export();
    }
}
