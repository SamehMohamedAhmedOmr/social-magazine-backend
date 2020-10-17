<?php

namespace Modules\Sections\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Sections\Http\Requests\CMS\MagazineInformationRequest;
use Modules\Sections\Services\CMS\MagazineInformationService;
use Throwable;

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

    /**
     * Store a newly created resource in storage.
     * @param MagazineInformationRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(MagazineInformationRequest $request)
    {
        return $this->magazineInformationService->store($request);
    }


}

