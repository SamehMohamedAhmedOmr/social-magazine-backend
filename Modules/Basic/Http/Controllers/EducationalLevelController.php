<?php

namespace Modules\Basic\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Basic\Services\Common\EducationalLevelService;

class EducationalLevelController extends Controller
{
    private $educationalLevelService;

    public function __construct(EducationalLevelService $educationalLevelService)
    {
        $this->educationalLevelService = $educationalLevelService;
    }
    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->educationalLevelService->index();
    }

}
