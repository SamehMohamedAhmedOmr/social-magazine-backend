<?php

namespace Modules\Article\Http\Controllers\Front;

use Illuminate\Routing\Controller;
use Modules\Article\Services\Frontend\ArticleSuggestedJudgesService;

class ArticleSuggestedJudgesController extends Controller
{

    private $service;

    public function __construct(ArticleSuggestedJudgesService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('article::index');
    }


}
