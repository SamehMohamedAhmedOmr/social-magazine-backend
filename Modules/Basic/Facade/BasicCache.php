<?php


namespace Modules\Basic\Facade;

use Illuminate\Support\Facades\Facade;

class BasicCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BasicCache';
    }
}
