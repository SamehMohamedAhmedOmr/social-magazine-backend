<?php

namespace Modules\Article\Services\Frontend;

use Modules\Article\Repositories\ArticleRepository;
use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Transformers\Front\AdvisoryBodyResource;

class ArticleManagementService extends LaravelServiceClass
{
    private $main_repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->main_repository = $repository;
    }

    public function index()
    {
        $content = CacheHelper::getCache(SectionsCache::advisoryBody());

        if (!$content) {
            $content = $this->main_repository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::advisoryBody(), $content);
        }

        $content = AdvisoryBodyResource::collection($content);
        return ApiResponse::format(200, $content);
    }

}
