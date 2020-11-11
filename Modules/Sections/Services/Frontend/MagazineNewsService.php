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


    public function LatestNews()
    {
        $content = CacheHelper::getCache(SectionsCache::latestMagazineNews());

        if (!$content) {
            $content = $this->main_repository->all([
                'is_active' => true
            ],[],6);
            CacheHelper::putCache(SectionsCache::latestMagazineNews(), $content);
        }

        $content->load([
            'images'
        ]);

        $content = MagazineNewsResource::collection($content);
        return ApiResponse::format(200, $content);
    }

    public function show($slug)
    {
        $content = $this->main_repository->get($slug,[
            'is_active' => true
        ],'slug',['images']);

        $content = $this->main_repository->updateVisitors($content);

        CacheHelper::forgetCache(SectionsCache::magazineNews());
        CacheHelper::forgetCache(SectionsCache::latestMagazineNews());

        $content = MagazineNewsResource::make($content);

        return ApiResponse::format(200, $content);
    }

}
