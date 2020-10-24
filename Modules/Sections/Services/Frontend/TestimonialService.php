<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\TestimonialRepository;
use Modules\Sections\Transformers\Front\TestimonialResource;

class TestimonialService extends LaravelServiceClass
{
    private $main_repository;

    public function __construct(TestimonialRepository $repository)
    {
        $this->main_repository = $repository;
    }

    public function index()
    {
        $content = CacheHelper::getCache(SectionsCache::testimonial());

        if (!$content) {
            $content = $this->main_repository->all([
                'is_active' => true
            ]);
            CacheHelper::putCache(SectionsCache::testimonial(), $content);
        }

        $content->load([
            'image'
        ]);

        $content = TestimonialResource::collection($content);
        return ApiResponse::format(200, $content);
    }

}
