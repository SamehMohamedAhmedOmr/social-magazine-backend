<?php


namespace Modules\FrontendUtilities\Facades;

use Illuminate\Support\Facades\Facade;

class PromocodeValidationHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PromocodeValidationHelper';
    }
}
