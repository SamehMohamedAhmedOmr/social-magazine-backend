<?php

namespace Modules\Sections\Services\CMS;

use Illuminate\Http\JsonResponse;
use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Facade\SectionsHelper;
use Modules\Sections\Repositories\MagazineInformationRepository;
use Modules\Sections\Transformers\Front\MagazineInformationResource;
use Throwable;

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

    /**
     * Handles Add New CMSUser
     *
     * @param null $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store($request = null)
    {
        return \DB::transaction(function () use ($request) {
            $request_data = SectionsHelper::prepareUpdateMagazineInformation($request->all());

            $information = $this->magazineInformationRepository->getFirstRecord();

            $information =  $this->magazineInformationRepository->update($information->id, $request_data);

            CacheHelper::forgetCache(SectionsCache::magazineInformation());

            $information = MagazineInformationResource::make($information);

            return ApiResponse::format(201, $information, 'Account Created!');
        });

    }

}
