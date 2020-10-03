<?php

namespace Modules\WareHouse\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\CountryRequest;
use Modules\WareHouse\Services\CMS\CountryService;

class CountryController extends Controller
{
    private $countryService;
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->countryService->index();
    }

    /**
     * Display a one resource.
     * @param CountryRequest $request
     * @param $id
     * @return void
     */
    public function show(CountryRequest $request, $id)
    {
        return $this->countryService->show($id);
    }

    /**
     * Store a newly created resource in storage.
     * @param CountryRequest $request
     * @return JsonResponse
     */
    public function store(CountryRequest $request)
    {
        return $this->countryService->store($request);
    }

    /**
     * Update the specified resource in storage.
     * @param CountryRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CountryRequest $request, $id)
    {
        return $this->countryService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     * @param CountryRequest $request
     * @return JsonResponse
     */
    public function destroy(CountryRequest $request)
    {
        return $this->countryService->delete($request->country);
    }

    public function export(PaginationRequest $request)
    {
        return $this->countryService->export();
    }
}
