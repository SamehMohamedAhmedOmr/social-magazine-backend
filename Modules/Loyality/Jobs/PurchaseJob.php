<?php

namespace Modules\Loyality\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $user_point_repo;
    protected $points;
    protected $points_logs_repo;
    protected $points_log_id;
    protected $expire_date;

    public function __construct($user_id, $points, $user_point_repo,
                                $points_logs_repo, $points_log_id, $expire_date)
    {
        $this->user_id = $user_id;
        $this->user_point_repo = $user_point_repo;
        $this->points = $points;
        $this->points_logs_repo = $points_logs_repo;
        $this->points_log_id = $points_log_id;
        $this->expire_date = $expire_date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->points_logs_repo->get($this->points_log_id)) {
            $this->points_logs_repo->update($this->points_log_id, ['status' => 'added']);
            $this->user_point_repo->add($this->user_id, $this->points);
            ExpirePoints::dispatch($this->user_id, $this->points_logs_repo, $this->user_point_repo)->delay($this->expire_date);
        }
    }
}
