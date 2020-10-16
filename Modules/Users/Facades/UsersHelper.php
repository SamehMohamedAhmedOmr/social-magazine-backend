<?php


namespace Modules\Users\Facades;

use Illuminate\Support\Facades\Facade;

class UsersHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UsersHelper';
    }
}
