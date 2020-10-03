<?php


namespace Modules\Configuration\Facades;

use Illuminate\Support\Facades\Facade;

class TenantHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'TenantHelper';
    }
}
