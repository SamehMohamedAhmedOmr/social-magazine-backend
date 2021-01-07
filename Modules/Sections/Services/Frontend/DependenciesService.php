<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\ActivityRepository;
use Modules\Sections\Repositories\AdvisoryBodyRepository;
use Modules\Sections\Repositories\EventsRepository;
use Modules\Sections\Repositories\MagazineCategoryRepository;
use Modules\Sections\Repositories\MagazineGoalsRepository;
use Modules\Sections\Repositories\MagazineInformationRepository;
use Modules\Sections\Repositories\MagazineNewsRepository;
use Modules\Sections\Repositories\PhotosRepository;
use Modules\Sections\Repositories\PublicationRuleRepository;
use Modules\Sections\Repositories\TestimonialRepository;
use Modules\Sections\Repositories\TrackerRepository;
use Modules\Sections\Repositories\VideosRepository;
use Modules\Sections\Repositories\WhoIsUsRepository;
use Modules\Sections\Transformers\Common\TrackerResource;
use Modules\Sections\Transformers\Front\ActivityResource;
use Modules\Sections\Transformers\Front\AdvisoryBodyResource;
use Modules\Sections\Transformers\Front\EventsResource;
use Modules\Sections\Transformers\Front\MagazineCategoryResource;
use Modules\Sections\Transformers\Front\MagazineGoalsResource;
use Modules\Sections\Transformers\Front\MagazineInformationResource;
use Modules\Sections\Transformers\Front\MagazineNewsResource;
use Modules\Sections\Transformers\Front\PhotoResource;
use Modules\Sections\Transformers\Front\PublicationRulesResource;
use Modules\Sections\Transformers\Front\TestimonialResource;
use Modules\Sections\Transformers\Front\VideoResource;
use Modules\Sections\Transformers\Front\WhoIsUsResource;

class DependenciesService extends LaravelServiceClass
{
    private $magazineNewsRepository, $testimonialRepository,
            $advisoryBodyRepository, $magazineCategoryRepository,
            $publicationRuleRepository, $whoIsUsRepository,
            $magazineGoalsRepository, $videosRepository,
            $activityRepository, $photosRepository,
            $eventsRepository,
            $magazineInformationRepository, $trackerRepository;

    public function __construct(MagazineNewsRepository $magazineNewsRepository,
                                MagazineInformationRepository $magazineInformationRepository,
                                TrackerRepository $trackerRepository,
                                AdvisoryBodyRepository $advisoryBodyRepository,
                                MagazineCategoryRepository $magazineCategoryRepository,
                                PublicationRuleRepository $publicationRuleRepository,
                                MagazineGoalsRepository $magazineGoalsRepository,
                                WhoIsUsRepository $whoIsUsRepository,
                                VideosRepository $videosRepository,
                                ActivityRepository $activityRepository,
                                PhotosRepository $photosRepository,
                                EventsRepository $eventsRepository,
                                TestimonialRepository $testimonialRepository)
    {
        $this->magazineNewsRepository = $magazineNewsRepository;
        $this->testimonialRepository = $testimonialRepository;
        $this->magazineInformationRepository = $magazineInformationRepository;
        $this->trackerRepository = $trackerRepository;

        $this->advisoryBodyRepository = $advisoryBodyRepository;
        $this->magazineCategoryRepository = $magazineCategoryRepository;
        $this->publicationRuleRepository = $publicationRuleRepository;
        $this->whoIsUsRepository = $whoIsUsRepository;
        $this->magazineGoalsRepository = $magazineGoalsRepository;

        $this->videosRepository = $videosRepository;
        $this->activityRepository = $activityRepository;
        $this->photosRepository = $photosRepository;
        $this->eventsRepository = $eventsRepository;
    }

    public function LatestNews()
    {
        $content = CacheHelper::getCache(SectionsCache::latestMagazineNews());

        if (!$content) {
            $content = $this->magazineNewsRepository->all([
                'is_active' => true
            ],[],6);

            $content->load([
                'images'
            ]);

            CacheHelper::putCache(SectionsCache::latestMagazineNews(), $content);
        }

        return MagazineNewsResource::collection($content);
    }

    public function testimonial()
    {
        $content = CacheHelper::getCache(SectionsCache::testimonial());

        if (!$content) {
            $content = $this->testimonialRepository->all([
                'is_active' => true
            ]);

            $content->load([
                'image'
            ]);


            CacheHelper::putCache(SectionsCache::testimonial(), $content);
        }

        return TestimonialResource::collection($content);
    }

    public function magazineInformation()
    {
        $information = CacheHelper::getCache(SectionsCache::magazineInformation());

        if (!$information){
            $information = $this->magazineInformationRepository->getFirstRecord();

            CacheHelper::putCache(SectionsCache::magazineInformation(), $information);
        }

        return MagazineInformationResource::make($information);
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

    public function mostViewedNews()
    {
        $content = CacheHelper::getCache(SectionsCache::mostViewedNews());

        if (!$content) {
            $content = $this->magazineNewsRepository->orderByViews([
                'is_active' => true
            ],5);

            $content->load([
                'images'
            ]);

            CacheHelper::putCache(SectionsCache::mostViewedNews(), $content);
        }

        return MagazineNewsResource::collection($content);
    }

    public function advisoryBody()
    {
        $content = CacheHelper::getCache(SectionsCache::advisoryBody());

        if (!$content) {
            $content = $this->advisoryBodyRepository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::advisoryBody(), $content);
        }

        return AdvisoryBodyResource::collection($content);
    }

    public function magazineCategories()
    {
        $content = CacheHelper::getCache(SectionsCache::magazineCategory());

        if (!$content) {
            $content = $this->magazineCategoryRepository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::magazineCategory(), $content);
        }

        $content->load([
            'images'
        ]);

        return MagazineCategoryResource::collection($content);
    }

    public function publicationRules()
    {
        $content = CacheHelper::getCache(SectionsCache::publicationRule());

        if (!$content) {
            $content = $this->publicationRuleRepository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::publicationRule(), $content);
        }

        return PublicationRulesResource::collection($content);
    }

    public function whoIsUs()
    {
        $whoIsUs = CacheHelper::getCache(SectionsCache::whoIsUs());

        if (!$whoIsUs) {
            $whoIsUs = $this->whoIsUsRepository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::whoIsUs(), $whoIsUs);
        }

        return WhoIsUsResource::collection($whoIsUs);
    }

    public function magazineGoals()
    {
        $goals = CacheHelper::getCache(SectionsCache::magazineGoals());

        if (!$goals){

            $goals = $this->magazineGoalsRepository->all([
                'is_active' => true
            ]);

            CacheHelper::putCache(SectionsCache::magazineGoals(), $goals);
        }

        return MagazineGoalsResource::collection($goals);
    }

    public function latestVideos()
    {
        $content = CacheHelper::getCache(SectionsCache::latestVideos());

        if (!$content) {
            $content = $this->videosRepository->all([
                'is_active' => true
            ],[],6);

            CacheHelper::putCache(SectionsCache::latestVideos(), $content);
        }

        return VideoResource::collection($content);
    }

    public function latestActivities()
    {
        $content = CacheHelper::getCache(SectionsCache::latestActivities());

        if (!$content) {
            $content = $this->activityRepository->all([
                'is_active' => true
            ],[],6);

            $content->load([
                'images'
            ]);

            CacheHelper::putCache(SectionsCache::latestActivities(), $content);
        }

        return ActivityResource::collection($content);
    }

    public function latestPhotos()
    {
        $content = CacheHelper::getCache(SectionsCache::latestPhotos());

        if (!$content) {
            $content = $this->photosRepository->all([
                'is_active' => true
            ],[],6);

            $content->load([
                'images'
            ]);

            CacheHelper::putCache(SectionsCache::latestPhotos(), $content);
        }

        return PhotoResource::collection($content);
    }

    public function latestEvents()
    {
        $content = CacheHelper::getCache(SectionsCache::latestEvents());

        if (!$content) {
            $content = $this->eventsRepository->all([
                'is_active' => true
            ],[],6);

            $content->load([
                'images'
            ]);

            CacheHelper::putCache(SectionsCache::latestEvents(), $content);
        }

        return EventsResource::collection($content);
    }

}
