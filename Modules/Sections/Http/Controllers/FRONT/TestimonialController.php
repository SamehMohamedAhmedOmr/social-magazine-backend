<?php

namespace Modules\Sections\Http\Controllers\FRONT;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Sections\Services\Frontend\TestimonialService;

class TestimonialController extends Controller
{

    private $service;

    public function __construct(TestimonialService $service)
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
