<?php


namespace Modules\ACL\Facade;

use Illuminate\Support\Facades\Facade;

class PermissionHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PermissionHelper';
    }
}
