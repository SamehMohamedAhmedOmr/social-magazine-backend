<?php

namespace Modules\Loyality\Services\Common;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Loyality\Repositories\Common\PointsLogRepository;
use Modules\Loyality\Repositories\Common\UserPointRepository;
use Modules\Loyality\Transformers\Common\PointsLogResource;

class UserPointsService extends LaravelServiceClass
{
    protected $user_point_repository;
    protected $points_log_repository;

    public function __construct(UserPointRepository $user_point_repository, PointsLogRepository $points_log_repository)
    {
        $this->user_point_repository = $user_point_repository;
        $this->points_log_repository = $points_log_repository;
    }

    public function get($user_id)
    {
        $data = $this->user_point_repository->getPoints($user_id);
        return ApiResponse::format(200, $data, 'Success');
    }

    public function log($user_id)
    {
        $points = PointsLogResource::collection($this->points_log_repository->getPointsLogs($user_id));

        return ApiResponse::format(200, $points, 'Success');
    }

}
