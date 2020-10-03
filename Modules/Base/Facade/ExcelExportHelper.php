<?php


namespace Modules\Base\Facade;

use Illuminate\Support\Facades\Facade;

class ExcelExportHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ExcelExportHelper';
    }
}
