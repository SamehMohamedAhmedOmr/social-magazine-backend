<?php

namespace Modules\PreArticle\Services;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\PreArticle\Facades\PreArticleCache;
use Modules\PreArticle\Repositories\ArticleFilterRepository;
use Modules\PreArticle\Repositories\ArticleStatusRepository;
use Modules\PreArticle\Transformers\ArticleFilter;
use Modules\PreArticle\Transformers\ArticleStatusType;

class PreArticleService extends LaravelServiceClass
{
    private $articleStatusRepository, $articleFilterRepository;

    public function __construct(ArticleStatusRepository $articleStatusRepository,
                                ArticleFilterRepository $articleFilterRepository)
    {
        $this->articleStatusRepository = $articleStatusRepository;
        $this->articleFilterRepository = $articleFilterRepository;

    }

    public function index()
    {
        $article_status = $this->articleStatus();
        $article_filter = $this->articleFilter();


        return ApiResponse::format(200, [
            'article_status' => $article_status,
            'article_filter' => $article_filter,
        ]);
    }


    public function articleStatus()
    {
        $content = CacheHelper::getCache(PreArticleCache::statusList());

        if (!$content) {
            $content = $this->articleStatusRepository->all();

            $content->load([
                'statusList'
            ]);

            CacheHelper::putCache(PreArticleCache::statusList(), $content);
        }


        return ArticleStatusType::collection($content);
    }

    public function articleFilter()
    {
        $content = CacheHelper::getCache(PreArticleCache::statusFilter());

        if (!$content) {
            $content = $this->articleFilterRepository->all();

            CacheHelper::putCache(PreArticleCache::statusFilter(), $content);
        }


        return ArticleFilter::collection($content);
    }


}
