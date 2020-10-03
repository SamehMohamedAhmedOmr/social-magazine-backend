<?php

namespace Modules\FrontendUtilities\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\FrontendUtilities\Services\Frontend\BannerServiceFront;

class BannerController extends Controller
{
    private $bannerService;

    public function __construct(BannerServiceFront $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->bannerService->index();
    }
}
