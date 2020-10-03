<?php


namespace Modules\Base\Facade;

use Illuminate\Support\Facades\Facade;

class DbHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'DbHelper';
    }
}
