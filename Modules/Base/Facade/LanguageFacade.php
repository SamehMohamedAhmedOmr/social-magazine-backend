<?php


namespace Modules\Base\Facade;

use Illuminate\Support\Facades\Facade;

class LanguageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'language';
    }
}
