<?php

namespace Modules\Loyality\Repositories\Common;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Loyality\Entities\PointLog;
use \DB;

class PointsLogRepository extends LaravelRepositoryClass
{
    private $user_point_repo;

    public function __construct(PointLog $point_log, UserPointRepository $user_point_repo)
    {
        $this->model = $point_log;
        $this->user_point_repo = $user_point_repo;
    }

    public function create(array $data, $with = [])
    {
        return $this->model->create($data);
    }

    public function transfer($from_user_id, $to_user_id, $points_needed)
    {
        $points_logs = $this->model->where('user_id', $from_user_id)
            ->where('points_gained', '>', 0)
            ->whereDate('expiration_date', '>=', now()->format('Y-m-d H:i:s'))
            ->whereDate('refund_date', '<', now()->format('Y-m-d H:i:s'))
            ->orderBy('created_at', 'desc')->get();

        $bulk_save_logs = [];
        foreach ($points_logs as $points_log) {
            if ($points_needed == 0) {
                break;
            }
            if ($points_log->points_gained <= $points_needed) {
                $points_log->update(['user_id' => $to_user_id, 'money_spent' => 0]);
                $points_needed -= $points_log->points_gained;
            } else {
                $points_log->points_gained -= $points_needed;
                $points_log->save();
                $bulk_save_logs[] = [
                    'user_id' => $to_user_id, 'points_gained' => $points_needed, 'order_id' => $points_log->order_id,
                    'refund_date' => $points_log->refund_date, 'expiration_date' => $points_log->expiration_date,
                ];
            }
        }

        if ($bulk_save_logs != []) {
            $this->model->insert($bulk_save_logs);
        }

        return $points_logs != null ? true : false;
    }

    public function returnOrder($order_id)
    {
        $log = $this->model->where('order_id', $order_id)->firstOrFail();

        if ($log->refund_date <= now()->format('Y-m-d H:i:s')) {
            return 'cannot be refunded, the last day of refunding was ' . $log->refund_date;
        }

        $points = $log->points_gained - $log->points_redeemed;
        $user_id = $log->user_id;

        $data['money_spent'] = $log->money_spent;
        $data['money_saved'] = $log->money_saved;

        // Remove Gained Points Or Add Redeemed Points
        $this->user_point_repo->add($user_id, $points);
        $log->delete();

        $data['user_points'] = $this->user_point_repo->getPoints($user_id);
        return $data;
    }

    public function getPointsLogs($user_id)
    {
        return $this->model->where('user_id', $user_id)->get()->load('order');
    }

    public function pluckAvailablePoints($user_id)
    {
        return $this->model->where('user_id', $user_id)->select(
            \DB::raw('SUM(points_gained - points_redeemed) as avail_points')
        )
            ->where('status', 'added')
            ->whereDate('expiration_date', '>=', now()->format('Y-m-d H:i:s'))
            ->get()
            ->sum('avail_points');
    }
}
