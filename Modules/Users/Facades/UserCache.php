<?php

namespace Modules\Users\Facades;

use Illuminate\Support\Facades\Facade;

class UserCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UserCache';
    }
}
