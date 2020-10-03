<?php

namespace Modules\WareHouse\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\WareHouse\Http\Requests\CountryRequest;
use Modules\WareHouse\Services\Frontend\DistrictService;

class DistrictController extends Controller
{
    private $districtService;
    public function __construct(DistrictService $districtService)
    {
        $this->districtService = $districtService;
    }

    /**
     * Display a listing of the resource.
     * @param CountryRequest $request
     * @return JsonResponse
     */
    public function districtsInCountry(CountryRequest $request)
    {
        return $this->districtService->index();
    }

    public function districtTree(PaginationRequest $request)
    {
        return $this->districtService->districtTree();
    }
}
