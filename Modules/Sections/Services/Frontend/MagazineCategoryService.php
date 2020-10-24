<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\MagazineCategoryRepository;
use Modules\Sections\Transformers\Front\MagazineCategoryResource;

class MagazineCategoryService extends LaravelServiceClass
{
    private $main_repository;

    public function __construct(MagazineCategoryRepository $repository)
    {
        $this->main_repository = $repository;
    }

    public function index()
    {
        $content = CacheHelper::getCache(SectionsCache::magazineCategory());

        if (!$content) {
            $content = $this->main_repository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::magazineCategory(), $content);
        }

        $content->load([
            'images'
        ]);

        $content = MagazineCategoryResource::collection($content);
        return ApiResponse::format(200, $content);
    }

}
