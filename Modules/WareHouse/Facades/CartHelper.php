<?php


namespace Modules\WareHouse\Facades;

use Illuminate\Support\Facades\Facade;

class CartHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CartHelper';
    }
}
