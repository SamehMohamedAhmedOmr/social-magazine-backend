<?php


namespace Modules\WareHouse\Facades;

use Illuminate\Support\Facades\Facade;

class StockHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'StockHelper';
    }
}
