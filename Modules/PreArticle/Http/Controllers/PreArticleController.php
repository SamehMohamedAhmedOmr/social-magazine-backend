<?php

namespace Modules\PreArticle\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\PreArticle\Services\PreArticleService;

class PreArticleController extends Controller
{

    private $preArticleService;

    public function __construct(PreArticleService $preArticleService)
    {
        $this->preArticleService = $preArticleService;

    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|void
     */
    public function index()
    {
        return $this->preArticleService->index();
    }


}
