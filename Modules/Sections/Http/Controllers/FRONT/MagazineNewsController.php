<?php

namespace Modules\Sections\Http\Controllers\FRONT;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Sections\Http\Requests\FRONT\MagazineNewsRequest;
use Modules\Sections\Services\Frontend\MagazineNewsService;

class MagazineNewsController extends Controller
{

    private $service;

    public function __construct(MagazineNewsService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request)
    {
        return $this->service->index();
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function LatestNews()
    {
        return $this->service->LatestNews();
    }

    /**
     * Display a listing of the resource.
     * @param MagazineNewsRequest $request
     * @return JsonResponse|void
     */
    public function get(MagazineNewsRequest $request)
    {
        return $this->service->show($request->slug);
    }

}
