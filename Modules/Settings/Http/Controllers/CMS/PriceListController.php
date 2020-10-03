<?php

namespace Modules\Settings\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Http\Requests\PriceListRequest;
use Modules\Settings\Services\CMS\PriceListService;

class PriceListController extends Controller
{
    private $price_list_service;

    public function __construct(PriceListService $price_list_service)
    {
        $this->price_list_service = $price_list_service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->price_list_service->index();
    }


    /**
     * Store a newly created resource in storage.
     * @param PriceListRequest $request
     * @return JsonResponse|void
     */
    public function store(PriceListRequest $request)
    {
        return $this->price_list_service->store();
    }

    /**
     * Show the specified resource.
     * @param PriceListRequest $request
     * @return JsonResponse|void
     */
    public function show(PriceListRequest $request)
    {
        return $this->price_list_service->show($request->price_list);
    }

    /**
     * Update the specified resource in storage.
     * @param PriceListRequest $request
     * @return JsonResponse|void
     */
    public function update(PriceListRequest $request)
    {
        return $this->price_list_service->update($request);
    }

    /**
     * Remove the specified resource from storage.
     * @param PriceListRequest $request
     * @return JsonResponse|void
     */
    public function destroy(PriceListRequest $request)
    {
        return $this->price_list_service->delete($request->price_list);
    }

    public function export(PaginationRequest $request)
    {
        return $this->price_list_service->export();
    }
}
