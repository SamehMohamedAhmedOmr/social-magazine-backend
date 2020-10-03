<?php

namespace Modules\Settings\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Settings\Services\Frontend\TimeSectionService;

class TimeSectionController extends Controller
{
    private $timeSectionService;
    public function __construct(TimeSectionService $timeSectionService)
    {
        $this->timeSectionService = $timeSectionService;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @return void
     */
    public function index(PaginationRequest $request)
    {
        return $this->timeSectionService->index();
    }
}
