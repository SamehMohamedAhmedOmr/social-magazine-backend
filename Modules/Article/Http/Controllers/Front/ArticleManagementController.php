<?php

namespace Modules\Article\Http\Controllers\Front;


use Illuminate\Routing\Controller;
use Modules\Article\Services\Frontend\ArticleManagementService;

class ArticleManagementController extends Controller
{

    private $service;

    public function __construct(ArticleManagementService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('article::index');
    }


}
