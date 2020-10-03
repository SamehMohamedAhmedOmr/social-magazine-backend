<?php

namespace Modules\ACL\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\ACL\Entities\Permissions;
use Modules\ACL\Facade\PermissionHelper;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $allowable = PermissionHelper::allowable();

        $route_name = \Route::currentRouteName();

        $contains = \Str::contains($route_name, $allowable);

        if ($contains) { // will has permissions
            $key = PermissionHelper::generateKey($route_name);

            $permission = Permissions::where('key', $key)->first();
            if (!$permission) {
                return $next($request);
            }

            $user_roles = PermissionHelper::getUserRoles();

            if (!count($user_roles)) {
                PermissionHelper::unAuthorized();
            }

            $user_roles->load('permissions');

            $has_permission = $this->validatePermission($user_roles, $permission);

            if (!$has_permission) {
                PermissionHelper::unAuthorized();
            }
        }

        return $next($request);
    }

    private function validatePermission($user_roles, $permission)
    {
        $has_permission = false;
        foreach ($user_roles as $role) {
            $target_permission = $role->permissions->where('id', $permission->id)->first();
            if (isset($target_permission)) {
                $has_permission = true;
                break;
            }
        }
        return $has_permission;
    }
}
