<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\PhotosRepository;
use Modules\Sections\Transformers\Front\PhotoResource;

class PhotosService extends LaravelServiceClass
{
    private $main_repository;

    public function __construct(PhotosRepository $repository)
    {
        $this->main_repository = $repository;
    }

    public function index()
    {
        list($contents, $pagination) = parent::paginate($this->main_repository, null, true,[
            'is_active' => 1
        ]);

        $contents->load([
            'images'
        ]);

        $contents = PhotoResource::collection($contents);
        return ApiResponse::format(200, $contents, null, $pagination);
    }


    public function latest()
    {
        $content = CacheHelper::getCache(SectionsCache::latestPhotos());

        if (!$content) {
            $content = $this->main_repository->all([
                'is_active' => true
            ],[],6);

            $content->load([
                'images'
            ]);

            CacheHelper::putCache(SectionsCache::latestPhotos(), $content);
        }

        $content = PhotoResource::collection($content);
        return ApiResponse::format(200, $content);
    }

    public function show($slug)
    {
        $content = $this->main_repository->get($slug,[
            'is_active' => true
        ],'slug',['images']);

        $content = PhotoResource::make($content);

        return ApiResponse::format(200, $content);
    }

}
