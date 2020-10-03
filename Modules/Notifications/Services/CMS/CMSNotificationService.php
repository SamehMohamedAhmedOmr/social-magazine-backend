<?php

namespace Modules\Notifications\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Notifications\Repositories\CMSNotificationRepository;

class CMSNotificationService extends LaravelServiceClass
{
    protected $CMSNotificationRepository;

    public function __construct(CMSNotificationRepository $CMSNotificationRepository)
    {
        $this->CMSNotificationRepository = $CMSNotificationRepository;
    }

    public function index()
    {
        $user = \Auth::user()->load('admin.notifications');
        $per_page = request('per_page') ?? 15;
        $response = $this->CMSNotificationRepository->index($user, $per_page);
        $pagination = \Pagination::preparePagination($response);
        return ApiResponse::format(200, $response->items(), 'successfully', $pagination);
    }

    public function markAsRead()
    {
        $user = \Auth::user()->load('admin.unreadNotifications');
        $response = $this->CMSNotificationRepository->markAsRead($user);
        return ApiResponse::format(200, [], 'successfully');
    }

    public function deleteNotifications()
    {
        $user = \Auth::user()->load('admin.notifications');
        $response = $this->CMSNotificationRepository->deleteNotifications($user);
        return ApiResponse::format(204, [], 'successfully');
    }
}
