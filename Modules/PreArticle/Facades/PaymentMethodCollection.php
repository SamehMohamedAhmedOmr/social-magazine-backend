<?php


namespace Modules\PreArticle\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentMethodCollection extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PaymentMethodCollection';
    }
}
