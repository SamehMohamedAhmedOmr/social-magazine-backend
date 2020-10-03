<?php

namespace Modules\Loyality\Http\Controllers\Common;

use Illuminate\Routing\Controller;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Loyality\Http\Requests\Common\AvailableLevelsRequest;
use Modules\Loyality\Http\Requests\Common\RedeemPointsRequest;
use Modules\Loyality\Http\Requests\Common\ValidatePointsRequest;
use Modules\Loyality\Services\Common\LevelService;
use Modules\Loyality\Services\Common\RedeemService;

class RedeemController extends Controller
{
    protected $redeem_service;

    protected $levelService;

    public function __construct(RedeemService $redeem_service, LevelService $levelService)
    {
        $this->redeem_service = $redeem_service;
        $this->levelService = $levelService;
    }

    public function availableLevels(AvailableLevelsRequest $request)
    {
        $data = $this->levelService->getLevels($request);
        return ApiResponse::format(200, $data, $data['is_levels'] === true ? 'There are levels' : 'There are no level');
    }

    public function validatePoints(ValidatePointsRequest $request)
    {
        $data = $this->redeem_service->validation($request);
        return ApiResponse::format(200, $data, 'Valid Points');
    }

    public function redeem(RedeemPointsRequest $request)
    {
        $data = $this->redeem_service->redeem($request);
        return ApiResponse::format(
            201,
            $data,
            'Added Successfully'
        );
    }
}
