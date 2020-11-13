<?php

namespace Modules\Basic\Services\Common;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Basic\Facade\BasicCache;
use Modules\Basic\Repositories\CountryRepository;
use Modules\Basic\Repositories\EducationalDegreeRepository;
use Modules\Basic\Repositories\EducationalLevelRepository;
use Modules\Basic\Repositories\GenderRepository;
use Modules\Basic\Repositories\TitleRepository;
use Modules\Basic\Transformers\CountryResource;
use Modules\Basic\Transformers\EducationalDegreeResource;
use Modules\Basic\Transformers\EducationalLevelResource;
use Modules\Basic\Transformers\GenderResource;
use Modules\Basic\Transformers\TitleResource;

class AccountDependenciesService extends LaravelServiceClass
{
    private $countryRepository, $educationalDegreeRepository,
            $educationalLevelRepository, $genderRepository, $titleRepository;

    public function __construct(CountryRepository $countryRepository,
                                EducationalLevelRepository $educationalLevelRepository,
                                GenderRepository $genderRepository,
                                TitleRepository $titleRepository,
                                EducationalDegreeRepository $educationalDegreeRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->educationalDegreeRepository = $educationalDegreeRepository;
        $this->genderRepository = $genderRepository;
        $this->educationalLevelRepository = $educationalLevelRepository;
        $this->titleRepository = $titleRepository;
    }

    public function index()
    {
        $country = $this->country();
        $degrees = $this->degrees();
        $levels = $this->levels();
        $genders = $this->genders();
        $titles = $this->titles();

        return ApiResponse::format(200, [
            'country' => $country,
            'degrees' => $degrees,
            'levels' => $levels,
            'genders' => $genders,
            'titles' => $titles,
        ]);
    }

    public function country()
    {
        $country = CacheHelper::getCache(BasicCache::country());

        if (!$country) {
            $country = $this->countryRepository->all();
            CacheHelper::putCache(BasicCache::country(), $country);
        }

        return CountryResource::collection($country);
    }

    public function degrees()
    {
        $degrees = CacheHelper::getCache(BasicCache::educationalDegrees());

        if (!$degrees){
            $degrees = $this->educationalDegreeRepository->all();
            CacheHelper::putCache(BasicCache::educationalDegrees(), $degrees);
        }

        return EducationalDegreeResource::collection($degrees);
    }

    public function levels()
    {
        $levels = CacheHelper::getCache(BasicCache::educationalLevels());

        if (!$levels){
            $levels = $this->educationalLevelRepository->all();
            CacheHelper::putCache(BasicCache::educationalLevels(), $levels);
        }
        return EducationalLevelResource::collection($levels);
    }

    public function genders()
    {
        $genders = CacheHelper::getCache(BasicCache::genders());

        if (!$genders){
            $genders = $this->genderRepository->all();
            CacheHelper::putCache(BasicCache::genders(), $genders);
        }

        return GenderResource::collection($genders);
    }

    public function titles()
    {
        $titles = CacheHelper::getCache(BasicCache::titles());

        if (!$titles){
            $titles = $this->titleRepository->all();
            CacheHelper::putCache(BasicCache::titles(), $titles);
        }

        return TitleResource::collection($titles);
    }

}
