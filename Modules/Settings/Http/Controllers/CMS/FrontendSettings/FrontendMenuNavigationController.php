<?php

namespace Modules\Settings\Http\Controllers\CMS\FrontendSettings;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Services\CMS\FrontendMenuNavigationTypeService;

class FrontendMenuNavigationController extends Controller
{

    private $frontendMenuNavigationTypeService;

    public function __construct(FrontendMenuNavigationTypeService $frontendMenuNavigationTypeService)
    {
        $this->frontendMenuNavigationTypeService = $frontendMenuNavigationTypeService;
    }

    /**
     * Show the specified resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->frontendMenuNavigationTypeService->index();
    }


}
