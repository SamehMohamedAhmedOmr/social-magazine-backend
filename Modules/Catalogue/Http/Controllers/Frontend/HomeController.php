<?php

namespace Modules\Catalogue\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Catalogue\Services\Frontend\HomeService;

class HomeController extends Controller
{
    private $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return void
     */
    public function index(PaginationRequest $request)
    {
        return $this->homeService->index();
    }
}
