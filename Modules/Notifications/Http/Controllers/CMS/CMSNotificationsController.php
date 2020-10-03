<?php

namespace Modules\Notifications\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Modules\Notifications\Services\CMS\CMSNotificationService;

class CMSNotificationsController extends Controller
{
    protected $CMSNotificationService;

    public function __construct(CMSNotificationService $CMSNotificationService)
    {
        $this->CMSNotificationService = $CMSNotificationService;
    }

    public function index()
    {
        return $this->CMSNotificationService->index();
    }

    public function update()
    {
        return $this->CMSNotificationService->markAsRead();
    }

    public function delete()
    {
        return $this->CMSNotificationService->deleteNotifications();
    }
}
