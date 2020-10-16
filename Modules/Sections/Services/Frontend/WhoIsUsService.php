<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Repositories\WhoIsUsRepository;
use Modules\Sections\Transformers\Front\WhoIsUsResource;

class WhoIsUsService extends LaravelServiceClass
{
    private $whoIsUsRepository;

    public function __construct(WhoIsUsRepository $whoIsUsRepository)
    {
        $this->whoIsUsRepository = $whoIsUsRepository;
    }

    public function index()
    {
        $whoIsUs = $this->whoIsUsRepository->all();
        $whoIsUs = WhoIsUsResource::collection($whoIsUs);
        return ApiResponse::format(200, $whoIsUs);
    }

}
