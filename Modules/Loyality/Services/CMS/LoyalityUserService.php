<?php

namespace Modules\Loyality\Services\CMS;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Loyality\Repositories\Common\UserPointRepository;

class LoyalityUserService extends LaravelServiceClass
{
    protected $userPointRepository;

    public function __construct(UserPointRepository $userPointRepository)
    {
        $this->userPointRepository = $userPointRepository;
    }

    public function addPoints($request)
    {
        $points = $this->userPointRepository->add($request->user_id, $request->points_needed);
        return ApiResponse::format(
            200,
            $points,
            'Added Successfully'
        );
    }

    public function removePoints($request)
    {
        $points_now = $this->userPointRepository->getPoints($request->user_id);
        if ($points_now['points'] < $request->points_needed) {
            $validator = Validator::make([], []);
            $validator->errors()->add('user_id', "cannot remove from user's points,
             user's points are ".$points_now['points']);
            new ValidationException($validator);
        }

        $points = $this->userPointRepository->add($request->user_id, -($request->points_needed));
        return ApiResponse::format(
            200,
            $points,
            'Removed Successfully'
        );
    }
}
