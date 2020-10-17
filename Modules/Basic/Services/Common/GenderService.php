<?php

namespace Modules\Basic\Services\Common;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Basic\Facade\BasicCache;
use Modules\Basic\Repositories\GenderRepository;
use Modules\Basic\Transformers\GenderResource;

class GenderService extends LaravelServiceClass
{
    private $genderRepository;

    public function __construct(GenderRepository $genderRepository)
    {
        $this->genderRepository = $genderRepository;
    }

    public function index()
    {
        $genders = CacheHelper::getCache(BasicCache::genders());

        if (!$genders){
            $genders = $this->genderRepository->all();
            CacheHelper::putCache(BasicCache::genders(), $genders);
        }

        $genders = GenderResource::collection($genders);
        return ApiResponse::format(200, $genders);
    }

}
