<?php


namespace Modules\FrontendUtilities\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\FrontendUtilities\Repositories\BannerRepository;
use Modules\FrontendUtilities\Transformers\BannerResource;

class BannerServiceFront
{
    private $banner_repository;

    public function __construct(BannerRepository $banner_repository)
    {
        $this->banner_repository = $banner_repository;
    }

    public function index()
    {
        $sort_key =  (request('sort_key') ? request('sort_key') : 'order');
        $sort_order =  (request('sort_order') ? request('sort_order') : 'desc');
        $banners = $this->banner_repository->getBannerFront(getLang(), $sort_key, $sort_order);
        $banners->load([
            'currentLanguage',
            'bannerImg.galleryType'
        ]);

        return ApiResponse::format(200, BannerResource::collection($banners), 'banner data retrieved successfully');
    }
}
