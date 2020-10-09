<?php

namespace Modules\Basic\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Basic\Services\Common\EducationalDegreeService;

class EducationalDegreeController extends Controller
{
    private $educationalDegreeService;

    public function __construct(EducationalDegreeService $educationalDegreeService)
    {
        $this->educationalDegreeService = $educationalDegreeService;
    }
    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->educationalDegreeService->index();
    }

}
