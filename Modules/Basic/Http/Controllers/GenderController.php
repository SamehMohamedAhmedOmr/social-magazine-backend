<?php

namespace Modules\Basic\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Basic\Services\Common\GenderService;

class GenderController extends Controller
{
    private $genderService;

    public function __construct(GenderService $genderService)
    {
        $this->genderService = $genderService;
    }
    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->genderService->index();
    }

}
