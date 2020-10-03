<?php

namespace Modules\Notifications\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Notifications\Jobs\DeviceNotifications;

class NotificationsService extends LaravelServiceClass
{
    public function sendNotifications()
    {
        DeviceNotifications::dispatchAfterResponse();

        return ApiResponse::format(200, [], 'successfully');
    }
}
