<?php

namespace Modules\Sections\Services\Common;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\TrackerRepository;
use Modules\Sections\Transformers\Common\TrackerResource;

class TrackerService extends LaravelServiceClass
{
    private $trackerRepository;

    public function __construct(TrackerRepository $trackerRepository)
    {
        $this->trackerRepository = $trackerRepository;
    }

    public function index()
    {
        $visitors = $this->visitorsCount();
        return ApiResponse::format(200, $visitors);
    }

    public function store()
    {
        $this->trackerRepository->store();

        CacheHelper::forgetCache(SectionsCache::visitors());

        $visitors = $this->visitorsCount();
        return ApiResponse::format(200, $visitors);
    }

    public function visitorsCount(){
        $visitors = CacheHelper::getCache(SectionsCache::visitors());

        if (!$visitors) {
            $visitors = $this->trackerRepository->count();
            CacheHelper::putCache(SectionsCache::visitors(), $visitors);
        }

        $number_of_visitors = collect([]);
        $number_of_visitors->put('visitors', $visitors);

        return TrackerResource::make($number_of_visitors);
    }

}
