<?php

namespace Modules\Sections\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Services\Common\DependenciesService;

class StatisticsService extends LaravelServiceClass
{
    private $service;

    public function __construct(DependenciesService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $news_count = $this->service->numberOfNews();
        $activities_count = $this->service->numberOfActivities();
        $events_count = $this->service->numberOfEvents();
        $videos_count = $this->service->numberOfVideos();

        $visitors = $this->service->visitorsCount();
        $advisory_bodies_count = $this->service->numberOfAdvisoryBody();


        return ApiResponse::format(200, [
            'visitors_count' => $visitors,
            'news_count' => $news_count,
            'activities_count' => $activities_count,
            'events_count' => $events_count,
            'videos_count' => $videos_count,
            'advisory_bodies_count' => $advisory_bodies_count,
        ]);
    }

}
