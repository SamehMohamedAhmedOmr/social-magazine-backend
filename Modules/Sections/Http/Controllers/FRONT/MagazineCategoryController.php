<?php

namespace Modules\Sections\Http\Controllers\FRONT;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Sections\Services\Frontend\MagazineCategoryService;

class MagazineCategoryController extends Controller
{
    private $service;

    public function __construct(MagazineCategoryService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->service->index();
    }

}
