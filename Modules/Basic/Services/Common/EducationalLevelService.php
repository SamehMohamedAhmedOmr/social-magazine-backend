<?php

namespace Modules\Basic\Services\Common;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Basic\Facade\BasicCache;
use Modules\Basic\Repositories\EducationalLevelRepository;
use Modules\Basic\Transformers\EducationalLevelResource;

class EducationalLevelService extends LaravelServiceClass
{
    private $educationalLevelRepository;

    public function __construct(EducationalLevelRepository $educationalLevelRepository)
    {
        $this->educationalLevelRepository = $educationalLevelRepository;
    }

    public function index()
    {
        $levels = CacheHelper::getCache(BasicCache::educationalLevels());

        if (!$levels){
            $levels = $this->educationalLevelRepository->all();
            CacheHelper::putCache(BasicCache::educationalLevels(), $levels);
        }
        $levels = EducationalLevelResource::collection($levels);
        return ApiResponse::format(200, $levels);
    }

}
