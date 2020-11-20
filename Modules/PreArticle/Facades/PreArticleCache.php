<?php


namespace Modules\PreArticle\Facades;

use Illuminate\Support\Facades\Facade;

class PreArticleCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PreArticleCache';
    }
}
