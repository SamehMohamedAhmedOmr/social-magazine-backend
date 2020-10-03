<?php

namespace Modules\Loyality\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Loyality\Http\Requests\CMS\LoyaliyProgramRequest;
use Modules\Loyality\Services\CMS\LoyalityProgramService;

class LoyalityProgramsController extends Controller
{
    private $loyality_program_service;

    public function __construct(LoyalityProgramService $loyality_program_service)
    {
        $this->loyality_program_service = $loyality_program_service;
    }

    public function show()
    {
        return $this->loyality_program_service->show();
    }

    public function updateOrCreate(LoyaliyProgramRequest $loyaliyProgramRequest)
    {
        return $this->loyality_program_service->updateOrCreate($loyaliyProgramRequest);
    }
}
