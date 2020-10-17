<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\WhoIsUsRepository;
use Modules\Sections\Transformers\Front\WhoIsUsResource;

class WhoIsUsService extends LaravelServiceClass
{
    private $whoIsUsRepository;

    public function __construct(WhoIsUsRepository $whoIsUsRepository)
    {
        $this->whoIsUsRepository = $whoIsUsRepository;
    }

    public function index()
    {
        $whoIsUs = CacheHelper::getCache(SectionsCache::whoIsUs());

        if (!$whoIsUs) {
            $whoIsUs = $this->whoIsUsRepository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::whoIsUs(), $whoIsUs);
        }

        $whoIsUs = WhoIsUsResource::collection($whoIsUs);
        return ApiResponse::format(200, $whoIsUs);
    }

}
