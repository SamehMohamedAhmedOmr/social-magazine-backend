<?php

namespace Modules\Sections\Http\Controllers\FRONT;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Sections\Services\Frontend\MagazineGoalsService;

class MagazineGoalsController extends Controller
{

    private $magazineGoalsService;

    public function __construct(MagazineGoalsService $magazineGoalsService)
    {
        $this->magazineGoalsService = $magazineGoalsService;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->magazineGoalsService->index();
    }

}
