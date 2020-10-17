<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\MagazineInformationRepository;
use Modules\Sections\Transformers\Front\MagazineInformationResource;

class MagazineInformationService extends LaravelServiceClass
{
    private $magazineInformationRepository;

    public function __construct(MagazineInformationRepository $magazineInformationRepository)
    {
        $this->magazineInformationRepository = $magazineInformationRepository;
    }

    public function index()
    {
        $information = CacheHelper::getCache(SectionsCache::magazineInformation());

        if (!$information){
            $information = $this->magazineInformationRepository->getFirstRecord();

            CacheHelper::putCache(SectionsCache::magazineInformation(), $information);
        }

        $information = MagazineInformationResource::make($information);

        return ApiResponse::format(200, $information);
    }

}
