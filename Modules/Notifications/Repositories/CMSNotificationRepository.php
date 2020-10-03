<?php


namespace Modules\Notifications\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;

class CMSNotificationRepository extends LaravelRepositoryClass
{
    public function index($user, $per_page = 15)
    {
        $admin = $user->admin;
        return $admin->notifications()->select('id', 'data', 'read_at')->paginate($per_page);
    }

    public function markAsRead($user)
    {
        $admin = $user->admin;
        $admin->unreadNotifications->markAsRead();

        return $admin->notifications;
    }

    public function deleteNotifications($user)
    {
        $admin = $user->admin;
        return $admin->notifications()->delete();
    }
}
