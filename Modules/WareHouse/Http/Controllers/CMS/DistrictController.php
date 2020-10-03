<?php

namespace Modules\WareHouse\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\DistrictRequest;
use Modules\WareHouse\Http\Requests\FilterDistrictRequest;
use Modules\WareHouse\Services\CMS\DistrictService;

class DistrictController extends Controller
{
    private $districtService;

    public function __construct(DistrictService $districtService)
    {
        $this->districtService = $districtService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->districtService->index();
    }

    /**
     * Display a one resource.
     * @param DistrictRequest $request
     * @param $id
     * @return void
     */
    public function show(DistrictRequest $request, $id)
    {
        return $this->districtService->show($id);
    }

    /**
     * Store a newly created resource in storage.
     * @param DistrictRequest $request
     * @return JsonResponse
     */
    public function store(DistrictRequest $request)
    {
        return $this->districtService->store($request);
    }

    /**
     * Update the specified resource in storage.
     * @param DistrictRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(DistrictRequest $request, $id)
    {
        return $this->districtService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param DistrictRequest $request
     * @return JsonResponse
     */
    public function destroy(DistrictRequest $request)
    {
        return $this->districtService->delete($request->district);
    }

    /**
     * Remove the specified resource from storage.
     * @param FilterDistrictRequest $request
     * @return JsonResponse
     */
    public function listDistrict(FilterDistrictRequest $request)
    {
        return $this->districtService->listDistrict();
    }

    public function export(PaginationRequest $request)
    {
        return $this->districtService->export();
    }
}
