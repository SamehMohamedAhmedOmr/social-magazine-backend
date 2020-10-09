<?php

namespace Modules\Basic\Services\Common;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Basic\Repositories\EducationalDegreeRepository;
use Modules\Basic\Transformers\EducationalDegreeResource;

class EducationalDegreeService extends LaravelServiceClass
{
    private $educationalDegreeRepository;

    public function __construct(EducationalDegreeRepository $educationalDegreeRepository)
    {
        $this->educationalDegreeRepository = $educationalDegreeRepository;
    }

    public function index()
    {
        $degrees = $this->educationalDegreeRepository->all();
        $degrees = EducationalDegreeResource::collection($degrees);
        return ApiResponse::format(200, $degrees);
    }

}
