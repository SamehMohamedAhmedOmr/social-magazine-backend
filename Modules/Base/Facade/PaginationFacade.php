<?php


namespace Modules\Base\Facade;

use Illuminate\Support\Facades\Facade;

class Pagination extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pagination';
    }
}
