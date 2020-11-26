<?php


namespace Modules\Article\Facade;

use Illuminate\Support\Facades\Facade;

class ArticleHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ArticleHelper';
    }
}
