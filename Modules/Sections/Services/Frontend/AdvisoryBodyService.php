<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\AdvisoryBodyRepository;
use Modules\Sections\Transformers\Front\AdvisoryBodyResource;

class AdvisoryBodyService extends LaravelServiceClass
{
    private $main_repository;

    public function __construct(AdvisoryBodyRepository $repository)
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
