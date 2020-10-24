<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\MagazineNewsRepository;
use Modules\Sections\Transformers\Front\MagazineNewsResource;

class MagazineNewsService extends LaravelServiceClass
{
    private $main_repository;

    public function __construct(MagazineNewsRepository $repository)
    {
        $this->main_repository = $repository;
    }

    public function index()
    {
        $content = CacheHelper::getCache(SectionsCache::magazineNews());

        if (!$content) {
            $content = $this->main_repository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::magazineNews(), $content);
        }

        $content->load([
            'images'
        ]);

        $content = MagazineNewsResource::collection($content);
        return ApiResponse::format(200, $content);
    }

}
