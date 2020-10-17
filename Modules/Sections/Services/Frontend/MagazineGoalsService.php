<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\MagazineGoalsRepository;
use Modules\Sections\Transformers\Front\MagazineGoalsResource;

class MagazineGoalsService extends LaravelServiceClass
{
    private $magazineGoalsRepository;

    public function __construct(MagazineGoalsRepository $magazineGoalsRepository)
    {
        $this->magazineGoalsRepository = $magazineGoalsRepository;
    }

    public function index()
    {
        $goals = CacheHelper::getCache(SectionsCache::magazineGoals());

        if (!$goals){

            $goals = $this->magazineGoalsRepository->all([
                'is_active' => true
            ]);

            CacheHelper::putCache(SectionsCache::magazineGoals(), $goals);
        }

        $goals = MagazineGoalsResource::collection($goals);
        return ApiResponse::format(200, $goals);
    }

}
