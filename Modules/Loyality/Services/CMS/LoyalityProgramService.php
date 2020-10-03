<?php

namespace Modules\Loyality\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Loyality\Repositories\CMS\LoyalityProgramRepository;
use Modules\Loyality\Transformers\CMS\LoyalityProgramResource;

class LoyalityProgramService extends LaravelServiceClass
{
    private $loyality_program_repo;

    public function __construct(LoyalityProgramRepository $loyality_program_repo)
    {
        $this->loyality_program_repo = $loyality_program_repo;
    }

    public function updateOrCreate($request)
    {
        $data = $this->loyality_program_repo->updateOrCreate($request->all());
        $data = LoyalityProgramResource::make($data);
        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function show($id = null)
    {
        $data = $this->loyality_program_repo->get(null);
        $data = isset($data) ? LoyalityProgramResource::make($data) : [];
        return ApiResponse::format(200, $data, 'Successful Query');
    }
}
