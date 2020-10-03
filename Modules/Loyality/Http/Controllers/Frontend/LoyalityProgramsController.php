<?php

namespace Modules\Loyality\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Modules\Loyality\Services\Frontend\LoyalityProgramService;

class LoyalityProgramsController extends Controller
{
    protected $loyalityProgramService;

    public function __construct(LoyalityProgramService $loyalityProgramService)
    {
        $this->loyalityProgramService = $loyalityProgramService;
    }

    public function show()
    {
        return $this->loyalityProgramService->show();
    }
}
