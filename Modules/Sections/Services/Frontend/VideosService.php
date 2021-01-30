<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\VideosRepository;
use Modules\Sections\Transformers\Front\VideoResource;

class VideosService extends LaravelServiceClass
{
    private $main_repository;

    public function __construct(VideosRepository $repository)
    {
        $this->main_repository = $repository;
    }

    public function index()
    {
        list($contents, $pagination) = parent::paginate($this->main_repository, null, true,[
            'is_active' => 1
        ]);

        $contents = VideoResource::collection($contents);
        return ApiResponse::format(200, $contents, null, $pagination);
    }


    public function latest()
    {
        $content = CacheHelper::getCache(SectionsCache::latestVideos());

        if (!$content) {
            $content = $this->main_repository->all([
                'is_active' => true
            ],[],6);

            CacheHelper::putCache(SectionsCache::latestVideos(), $content);
        }

        $content = VideoResource::collection($content);
        return ApiResponse::format(200, $content);
    }

    public function show($slug)
    {
        $content = $this->main_repository->get($slug,[
            'is_active' => true
        ],'slug');

        $content = VideoResource::make($content);

        return ApiResponse::format(200, $content);
    }

}
