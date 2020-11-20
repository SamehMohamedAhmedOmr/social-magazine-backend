<?php


namespace Modules\PreArticle\Facades;

use Illuminate\Support\Facades\Facade;

class ArticleTypeCollection extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ArticleTypeCollection';
    }
}
