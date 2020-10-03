<?php

namespace Modules\Notifications\Http\Controllers\CMS;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Notifications\Http\Requests\NotificationRequest;
use Modules\Notifications\Services\CMS\EmailService;
use Modules\Notifications\Services\CMS\NotificationsService;

class NotificationsController extends Controller
{
    private $notifications_service;
    private $email_service;

    public function __construct(NotificationsService $notifications_service, EmailService $email_service)
    {
        $this->notifications_service = $notifications_service;
        $this->email_service = $email_service;
    }

    /**
     * Notify
     * @param NotificationRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function notify(NotificationRequest $request)
    {
        return $this->notifications_service->sendNotifications();
    }
}
