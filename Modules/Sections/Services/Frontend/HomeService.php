<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Services\Common\DependenciesService;


class HomeService extends LaravelServiceClass
{
    private $service;

    public function __construct(DependenciesService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $latestNews = $this->service->LatestNews();
        $testimonial = $this->service->testimonial();
        $magazine_information = $this->service->magazineInformation();
        $visitors = $this->service->visitorsCount();
        $most_viewed_news = $this->service->mostViewedNews();

        $latest_videos = $this->service->latestVideos();
        $latest_activities = $this->service->latestActivities();
        $latest_photos = $this->service->latestPhotos();
        $latest_events = $this->service->latestEvents();

        return ApiResponse::format(200, [
            'latest_news' => $latestNews,
            'testimonial' => $testimonial,
            'magazine_information' => $magazine_information,
            'visitors_count' => $visitors,
            'most_viewed_news' => $most_viewed_news,

            'latest_videos' => $latest_videos,
            'latest_activities' => $latest_activities,
            'latest_photos' => $latest_photos,
            'latest_events' => $latest_events,
        ]);
    }

    public function dependencies()
    {
        $advisory_body = $this->service->advisoryBody();
        $magazine_categories = $this->service->magazineCategories();
        $publication_rules = $this->service->publicationRules();
        $who_is_us = $this->service->whoIsUs();
        $magazine_goals = $this->service->magazineGoals();


        return ApiResponse::format(200, [
            'advisory_body' => $advisory_body,
            'magazine_categories' => $magazine_categories,
            'publication_rules' => $publication_rules,
            'who_is_us' => $who_is_us,
            'magazine_goals' => $magazine_goals,
        ]);
    }

}
