<?php

namespace Modules\ACL\Helpers;

use Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class PermissionHelper
{
    public function allowable()
    {
        return [
            'admins.',
        ];
    }

    public function generateKey($route_name)
    {
        $key = str_replace('.', '_', strtoupper($route_name));
        return str_replace('-', '_', $key);
    }

    public function notAbleToDeleteRole()
    {
        throw ValidationException::withMessages([
            'roles' => trans('acl::errors.notAbleToDeleteRole'),
        ]);
    }

    public function getUserRoles()
    {
        return Auth::user()->roles;
    }

    public function unAuthorized()
    {
        throw new UnauthorizedException(trans('acl::errors.unAuthorized'), 403);
    }
}
