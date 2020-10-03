<?php


namespace Modules\Catalogue\Facades;

use Illuminate\Support\Facades\Facade;

class ProductHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ProductHelper';
    }
}
