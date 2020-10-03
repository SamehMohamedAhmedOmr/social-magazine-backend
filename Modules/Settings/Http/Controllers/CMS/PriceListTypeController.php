<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\PriceListTypeRequest;
use Modules\Settings\Services\CMS\PriceListTypeService;

class PriceListTypeController extends Controller
{
    private $price_list_type_service;

    public function __construct(PriceListTypeService $price_list_type_service)
    {
        $this->price_list_type_service = $price_list_type_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return  $this->price_list_type_service->index();
    }

    /**
     * Store a newly created resource in storage.
     * @param PriceListTypeRequest $request
     * @return JsonResponse
     */
    public function store(PriceListTypeRequest $request)
    {
        return $this->price_list_type_service->store();
    }

    /**
     * Show the specified resource.
     * @param PriceListTypeRequest $request
     * @return JsonResponse
     */
    public function show(PriceListTypeRequest $request)
    {
        return $this->price_list_type_service->show($request->price_list_type);
    }

    /**
     * Update the specified resource in storage.
     * @param PriceListTypeRequest $request
     * @return JsonResponse
     */
    public function update(PriceListTypeRequest $request)
    {
        return $this->price_list_type_service->update($request);
    }

    /**
     * Remove the specified resource from storage.
     * @param PriceListTypeRequest $request
     * @return JsonResponse
     */
    public function destroy(PriceListTypeRequest $request)
    {
        return $this->price_list_type_service->delete($request->price_list_type);
    }
}
