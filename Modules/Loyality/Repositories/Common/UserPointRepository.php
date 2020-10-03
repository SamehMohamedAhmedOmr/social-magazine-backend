<?php

namespace Modules\Loyality\Repositories\Common;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Loyality\Entities\PointLog;
use Modules\Loyality\Entities\UserPoint;

class UserPointRepository extends LaravelRepositoryClass
{
    protected $points_log;

    public function __construct(UserPoint $user_point, PointLog $point_log)
    {
        $this->model = $user_point;
        $this->points_log = $point_log;
    }

    public function add($user_id, $points)
    {
        $user_points = $this->model->firstOrCreate(['user_id' => $user_id]);
        $user_points->update(['points' => $user_points->points + $points]);
        return $user_points;
    }

    public function getPoints($user_id)
    {
        $user = $this->model->where('user_id', $user_id)->first();
        $data['points'] = $user ? $user->points : 0;
        $data['pending_points'] = $this->points_log
            ->where('user_id', $user_id)->where('status', 'pending')
            ->where('expiration_date', '>=', now()->format('Y-m-d H:i:s'))
            ->where('points_gained', '>', 0)
            ->sum('points_gained') ?? 0;

        return $data;
    }

    public function transfer($from_user_id, $to_user_id, $points)
    {
        $this->add($from_user_id, -$points);
        $this->add($to_user_id, $points);

        return $this->getPoints($from_user_id);
    }

    public function sumPointsRedeemed($user_id)
    {
        return $this->points_log->where('user_id', $user_id)->sum('money_saved');
    }
}
