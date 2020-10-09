<?php

namespace Modules\Basic\Services\Common;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Basic\Repositories\CountryRepository;
use Modules\Basic\Repositories\GenderRepository;
use Modules\Basic\Transformers\CountryResource;
use Modules\Basic\Transformers\GenderResource;

class GenderService extends LaravelServiceClass
{
    private $genderRepository;

    public function __construct(GenderRepository $genderRepository)
    {
        $this->genderRepository = $genderRepository;
    }

    public function index()
    {
        $genders = $this->genderRepository->all();
        $genders = GenderResource::collection($genders);
        return ApiResponse::format(200, $genders);
    }

}
