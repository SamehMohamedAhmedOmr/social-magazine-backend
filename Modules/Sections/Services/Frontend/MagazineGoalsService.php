<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Repositories\MagazineGoalsRepository;
use Modules\Sections\Transformers\Front\MagazineGoalsResource;

class MagazineGoalsService extends LaravelServiceClass
{
    private $magazineGoalsRepository;

    public function __construct(MagazineGoalsRepository $magazineGoalsRepository)
    {
        $this->magazineGoalsRepository = $magazineGoalsRepository;
    }

    public function index()
    {
        $goals = $this->magazineGoalsRepository->all();
        $goals = MagazineGoalsResource::collection($goals);
        return ApiResponse::format(200, $goals);
    }

}
