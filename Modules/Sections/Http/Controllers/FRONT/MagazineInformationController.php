<?php

namespace Modules\Sections\Http\Controllers\FRONT;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Sections\Services\Frontend\MagazineInformationService;

class MagazineInformationController extends Controller
{

    private $magazineInformationService;

    public function __construct(MagazineInformationService $magazineInformationService)
    {
        $this->magazineInformationService = $magazineInformationService;
    }


    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->magazineInformationService->index();
    }

}
