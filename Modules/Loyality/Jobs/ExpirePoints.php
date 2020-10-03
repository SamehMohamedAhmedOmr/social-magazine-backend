<?php

namespace Modules\Loyality\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExpirePoints implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $points_logs_repo;
    protected $user_point_repo;

    public function __construct($user_id, $points_logs_repo, $user_point_repo)
    {
        $this->user_id = $user_id;
        $this->points_logs_repo = $points_logs_repo;
        $this->user_point_repo = $user_point_repo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user_point_repo->update($this->user_id, [
            'points' => $this->points_logs_repo->pluckAvailablePoints($this->user_id)
        ], [], 'user_id');
    }
}
