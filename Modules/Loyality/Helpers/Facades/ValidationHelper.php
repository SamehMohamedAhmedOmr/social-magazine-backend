<?php

namespace Modules\Loyality\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

class ValidationHelper extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'loyalityValidation';
    }
}
