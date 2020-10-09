<?php

namespace Modules\Basic\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Basic\Services\Common\TitleService;

class TitleController extends Controller
{
    private $titleService;

    public function __construct(TitleService $titleService)
    {
        $this->titleService = $titleService;
    }
    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->titleService->index();
    }

}
