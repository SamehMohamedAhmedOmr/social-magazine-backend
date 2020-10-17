<?php


namespace Modules\Sections\Facade;

use Illuminate\Support\Facades\Facade;

class SectionsCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SectionsCache';
    }
}
