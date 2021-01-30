<?php


namespace Modules\Article\Facade;

use Illuminate\Support\Facades\Facade;

class ArticleCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ArticleCache';
    }
}
