<?php

namespace Modules\FrontendUtilities\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\FrontendUtilities\Services\Frontend\PromocodeService;
use Modules\FrontendUtilities\Http\Requests\PromocodeValidateRequest;

class PromocodeController extends Controller
{
    private $promocode_service;

    public function __construct(PromocodeService $promocode_service)
    {
        $this->promocode_service = $promocode_service;
    }

    /**
     * Validate the given promcode.
     * @param PromocodeValidateRequest $request
     * @return JsonResponse
     */
    public function validate(PromocodeValidateRequest $request)
    {
        return ApiResponse::format(200, $this->promocode_service->validate($request->promocode), 'Validated successfully');
    }
}
