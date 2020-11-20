<?php


namespace Modules\PreArticle\Facades;

use Illuminate\Support\Facades\Facade;

class ArticleSubjectCollection extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ArticleSubjectCollection';
    }
}
