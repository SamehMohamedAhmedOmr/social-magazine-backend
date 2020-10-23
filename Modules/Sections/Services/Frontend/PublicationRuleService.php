<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\PublicationRuleRepository;
use Modules\Sections\Repositories\WhoIsUsRepository;
use Modules\Sections\Transformers\Front\PublicationRulesResource;
use Modules\Sections\Transformers\Front\WhoIsUsResource;

class PublicationRuleService extends LaravelServiceClass
{
    private $main_repository;

    public function __construct(PublicationRuleRepository $repository)
    {
        $this->main_repository = $repository;
    }

    public function index()
    {
        $content = CacheHelper::getCache(SectionsCache::publicationRule());

        if (!$content) {
            $content = $this->main_repository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::publicationRule(), $content);
        }

        $content = PublicationRulesResource::collection($content);
        return ApiResponse::format(200, $content);
    }

}
