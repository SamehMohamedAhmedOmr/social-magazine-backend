<?php

namespace Modules\Settings\Http\Controllers\CMS\FrontendSettings;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\FrontendSettings\FrontendMenuRequest;
use Modules\Settings\Services\CMS\FrontendMenuNavigationService;

class MenuController extends Controller
{
    private $menu_service;

    public function __construct(FrontendMenuNavigationService $menu_service)
    {
        $this->menu_service = $menu_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->menu_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param FrontendMenuRequest $request
     * @return JsonResponse|void
     */
    public function store(FrontendMenuRequest $request)
    {
        return $this->menu_service->store($request);
    }

    /**
     * Show the specified resource.
     * @param FrontendMenuRequest $request
     * @return JsonResponse|void
     */
    public function show(FrontendMenuRequest $request)
    {
        return $this->menu_service->show($request->menu);
    }

    /**
     * Update the specified resource in storage.
     * @param FrontendMenuRequest $request
     * @return JsonResponse|void
     */
    public function update(FrontendMenuRequest $request)
    {
        return $this->menu_service->update($request->menu, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param FrontendMenuRequest $request
     * @return JsonResponse|void
     */
    public function destroy(FrontendMenuRequest $request)
    {
        return $this->menu_service->delete($request->menu);
    }

    public function export(PaginationRequest $request)
    {
        return $this->menu_service->export();
    }
}
