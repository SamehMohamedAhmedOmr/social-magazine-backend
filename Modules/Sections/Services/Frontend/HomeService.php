<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\MagazineInformationRepository;
use Modules\Sections\Repositories\MagazineNewsRepository;
use Modules\Sections\Repositories\TestimonialRepository;
use Modules\Sections\Repositories\TrackerRepository;
use Modules\Sections\Transformers\Common\TrackerResource;
use Modules\Sections\Transformers\Front\MagazineInformationResource;
use Modules\Sections\Transformers\Front\MagazineNewsResource;
use Modules\Sections\Transformers\Front\TestimonialResource;

class HomeService extends LaravelServiceClass
{
    private $magazineNewsRepository, $testimonialRepository,
            $magazineInformationRepository, $trackerRepository;

    public function __construct(MagazineNewsRepository $magazineNewsRepository,
                                MagazineInformationRepository $magazineInformationRepository,
                                TrackerRepository $trackerRepository,
                                TestimonialRepository $testimonialRepository)
    {
        $this->magazineNewsRepository = $magazineNewsRepository;
        $this->testimonialRepository = $testimonialRepository;
        $this->magazineInformationRepository = $magazineInformationRepository;
        $this->trackerRepository = $trackerRepository;
    }

    public function index()
    {
        $latestNews = $this->LatestNews();
        $testimonial = $this->testimonial();
        $magazine_information = $this->magazineInformation();
        $visitors = $this->visitorsCount();
        $most_download_article = $this->mostDownloadedArticle();
        $most_viewed_article = $this->mostViewedArticle();
        $archive = $this->archive();

        return ApiResponse::format(200, [
            'latest_news' => $latestNews,
            'testimonial' => $testimonial,
            'magazine_information' => $magazine_information,
            'visitors_count' => $visitors,
            'most_download_article' => $most_download_article,
            'most_viewed_article' => $most_viewed_article,
            'archive' => $archive
        ]);
    }

    public function LatestNews()
    {
        $content = CacheHelper::getCache(SectionsCache::latestMagazineNews());

        if (!$content) {
            $content = $this->magazineNewsRepository->all([
                'is_active' => true
            ],[],6);
            CacheHelper::putCache(SectionsCache::latestMagazineNews(), $content);
        }

        $content->load([
            'images'
        ]);

        return MagazineNewsResource::collection($content);
    }

    public function testimonial()
    {
        $content = CacheHelper::getCache(SectionsCache::testimonial());

        if (!$content) {
            $content = $this->testimonialRepository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::testimonial(), $content);
        }

        $content->load([
            'image'
        ]);

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

    public function mostViewedArticle(){
        return [];
    }

    public function mostDownloadedArticle(){
        return [];
    }

    public function archive(){
        return [];
    }

}
