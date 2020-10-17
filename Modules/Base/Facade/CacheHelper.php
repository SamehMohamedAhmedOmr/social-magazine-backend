<?php


namespace Modules\Base\Facade;

use Illuminate\Support\Facades\Facade;

class CacheHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CacheHelper';
    }
}
