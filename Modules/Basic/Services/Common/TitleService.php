<?php

namespace Modules\Basic\Services\Common;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Basic\Facade\BasicCache;
use Modules\Basic\Repositories\TitleRepository;
use Modules\Basic\Transformers\TitleResource;

class TitleService extends LaravelServiceClass
{
    private $titleRepository;

    public function __construct(TitleRepository $titleRepository)
    {
        $this->titleRepository = $titleRepository;
    }

    public function index()
    {
        $titles = CacheHelper::getCache(BasicCache::titles());

        if (!$titles){
            $titles = $this->titleRepository->all();
            CacheHelper::putCache(BasicCache::titles(), $titles);
        }

        $titles = TitleResource::collection($titles);
        return ApiResponse::format(200, $titles);
    }

}
