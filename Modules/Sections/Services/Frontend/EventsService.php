<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Facade\SectionsCache;
use Modules\Sections\Repositories\EventsRepository;
use Modules\Sections\Transformers\Front\EventsResource;

class EventsService extends LaravelServiceClass
{
    private $main_repository;

    public function __construct(EventsRepository $repository)
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

        $contents = EventsResource::collection($contents);
        return ApiResponse::format(200, $contents, null, $pagination);
    }


    public function latest()
    {
        $content = CacheHelper::getCache(SectionsCache::latestEvents());

        if (!$content) {
            $content = $this->main_repository->all([
                'is_active' => true
            ],[],6);

            $content->load([
                'images'
            ]);

            CacheHelper::putCache(SectionsCache::latestEvents(), $content);
        }

        $content = EventsResource::collection($content);
        return ApiResponse::format(200, $content);
    }

    public function show($slug)
    {
        $content = $this->main_repository->get($slug,[
            'is_active' => true
        ],'slug',['images']);

        $content = EventsResource::make($content);

        return ApiResponse::format(200, $content);
    }

}
