<?php

namespace Modules\Sections\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Repositories\MagazineInformationRepository;
use Modules\Sections\Transformers\Front\MagazineInformationResource;

class MagazineInformationService extends LaravelServiceClass
{
    private $magazineInformationRepository;

    public function __construct(MagazineInformationRepository $magazineInformationRepository)
    {
        $this->magazineInformationRepository = $magazineInformationRepository;
    }

    public function index()
    {
        $information = $this->magazineInformationRepository->all();
        $information = MagazineInformationResource::collection($information);
        return ApiResponse::format(200, $information);
    }

}
