<?php

namespace Modules\WareHouse\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\WareshouseRequest;
use Modules\WareHouse\Services\CMS\WarehouseService;

class WarehouseController extends Controller
{
    private $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->warehouseService->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param WareshouseRequest $request
     * @return JsonResponse
     */
    public function store(WareshouseRequest $request)
    {
        return $this->warehouseService->store();
    }

    /**
     * Show the specified resource.
     * @param WareshouseRequest $request
     * @return JsonResponse
     */
    public function show(WareshouseRequest $request)
    {
        return $this->warehouseService->show($request->warehouse);
    }

    /**
     * Update the specified resource in storage.
     * @param WareshouseRequest $request
     * @return JsonResponse
     */
    public function update(WareshouseRequest $request)
    {
        return $this->warehouseService->update($request->warehouse);
    }

    /**
     * Remove the specified resource from storage.
     * @param WareshouseRequest $request
     * @return JsonResponse
     */
    public function destroy(WareshouseRequest $request)
    {
        return $this->warehouseService->delete($request->warehouse);
    }

    public function export(PaginationRequest $request)
    {
        return $this->warehouseService->export();
    }
}
