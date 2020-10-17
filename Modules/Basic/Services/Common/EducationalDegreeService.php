<?php

namespace Modules\Basic\Services\Common;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Basic\Facade\BasicCache;
use Modules\Basic\Repositories\EducationalDegreeRepository;
use Modules\Basic\Transformers\EducationalDegreeResource;

class EducationalDegreeService extends LaravelServiceClass
{
    private $educationalDegreeRepository;

    public function __construct(EducationalDegreeRepository $educationalDegreeRepository)
    {
        $this->educationalDegreeRepository = $educationalDegreeRepository;
    }

    public function index()
    {
        $degrees = CacheHelper::getCache(BasicCache::educationalDegrees());

        if (!$degrees){
            $degrees = $this->educationalDegreeRepository->all();
            CacheHelper::putCache(BasicCache::educationalDegrees(), $degrees);
        }

        $degrees = EducationalDegreeResource::collection($degrees);
        return ApiResponse::format(200, $degrees);
    }

}
