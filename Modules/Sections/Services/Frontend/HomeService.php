<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\AdvisoryBodyRepository;
use Modules\Sections\Repositories\MagazineCategoryRepository;
use Modules\Sections\Repositories\MagazineGoalsRepository;
use Modules\Sections\Repositories\MagazineInformationRepository;
use Modules\Sections\Repositories\MagazineNewsRepository;
use Modules\Sections\Repositories\PublicationRuleRepository;
use Modules\Sections\Repositories\TestimonialRepository;
use Modules\Sections\Repositories\TrackerRepository;
use Modules\Sections\Repositories\WhoIsUsRepository;
use Modules\Sections\Transformers\Common\TrackerResource;
use Modules\Sections\Transformers\Front\AdvisoryBodyResource;
use Modules\Sections\Transformers\Front\MagazineCategoryResource;
use Modules\Sections\Transformers\Front\MagazineGoalsResource;
use Modules\Sections\Transformers\Front\MagazineInformationResource;
use Modules\Sections\Transformers\Front\MagazineNewsResource;
use Modules\Sections\Transformers\Front\PublicationRulesResource;
use Modules\Sections\Transformers\Front\TestimonialResource;
use Modules\Sections\Transformers\Front\WhoIsUsResource;

class HomeService extends LaravelServiceClass
{
    private $magazineNewsRepository, $testimonialRepository,
            $advisoryBodyRepository, $magazineCategoryRepository,
            $publicationRuleRepository, $whoIsUsRepository,
            $magazineGoalsRepository,
            $magazineInformationRepository, $trackerRepository;

    public function __construct(MagazineNewsRepository $magazineNewsRepository,
                                MagazineInformationRepository $magazineInformationRepository,
                                TrackerRepository $trackerRepository,
                                AdvisoryBodyRepository $advisoryBodyRepository,
                                MagazineCategoryRepository $magazineCategoryRepository,
                                PublicationRuleRepository $publicationRuleRepository,
                                MagazineGoalsRepository $magazineGoalsRepository,
                                WhoIsUsRepository $whoIsUsRepository,
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
    }

    public function index()
    {
        $latestNews = $this->LatestNews();
        $testimonial = $this->testimonial();
        $magazine_information = $this->magazineInformation();
        $visitors = $this->visitorsCount();
        $most_viewed_news = $this->mostViewedNews();

        $advisory_body = $this->advisoryBody();
        $magazine_categories = $this->magazineCategories();
        $publication_rules = $this->publicationRules();
        $who_is_us = $this->whoIsUs();
        $magazine_goals = $this->magazineGoals();


        return ApiResponse::format(200, [
            'latest_news' => $latestNews,
            'testimonial' => $testimonial,
            'magazine_information' => $magazine_information,
            'visitors_count' => $visitors,
            'most_viewed_news' => $most_viewed_news,
            'advisory_body' => $advisory_body,
            'magazine_categories' => $magazine_categories,
            'publication_rules' => $publication_rules,
            'who_is_us' => $who_is_us,
            'magazine_goals' => $magazine_goals,
        ]);
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
}
