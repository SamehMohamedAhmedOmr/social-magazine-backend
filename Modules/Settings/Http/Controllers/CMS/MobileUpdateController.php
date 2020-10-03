<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\MobileUpdateRequest;
use Modules\Settings\Services\CMS\MobileUpdateService;

class MobileUpdateController extends Controller
{
    private $mobile_update_service;

    public function __construct(MobileUpdateService $mobile_update_service)
    {
        $this->mobile_update_service = $mobile_update_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->mobile_update_service->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param MobileUpdateRequest $request
     * @return JsonResponse|void
     */
    public function store(MobileUpdateRequest $request)
    {
        return $this->mobile_update_service->store();
    }

    /**
     * Show the specified resource.
     * @param MobileUpdateRequest $request
     * @return JsonResponse|void
     */
    public function show(MobileUpdateRequest $request)
    {
        return $this->mobile_update_service->show($request->mobile_update);
    }

    /**
     * Update the specified resource in storage.
     * @param MobileUpdateRequest $request
     * @return JsonResponse|void
     */
    public function update(MobileUpdateRequest $request)
    {
        return $this->mobile_update_service->update($request->mobile_update);
    }

    /**
     * Remove the specified resource from storage.
     * @param MobileUpdateRequest $request
     * @return JsonResponse|void
     */
    public function destroy(MobileUpdateRequest $request)
    {
        return $this->mobile_update_service->delete($request->mobile_update);
    }

    public function export(PaginationRequest $request)
    {
        return $this->mobile_update_service->export();
    }
}
