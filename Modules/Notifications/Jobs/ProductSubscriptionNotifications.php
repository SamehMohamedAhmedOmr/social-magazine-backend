<?php

namespace Modules\Notifications\Jobs;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notifications\Services\CMS\FirebaseNotificationService;

class ProductSubscriptionNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;
    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @param FirebaseNotificationService $service
     * @return void
     * @throws Exception
     */
    public function handle(FirebaseNotificationService $service)
    {
        $service->products = \Session::get('product_notifications');
        $service->ProductNotifyMe();
    }

}
