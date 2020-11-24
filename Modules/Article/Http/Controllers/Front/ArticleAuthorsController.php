<?php

namespace Modules\Article\Http\Controllers\Front;


use Illuminate\Routing\Controller;
use Modules\Article\Services\Frontend\ArticleAuthorsService;

class ArticleAuthorsController extends Controller
{

    private $service;

    public function __construct(ArticleAuthorsService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('article::index');
    }


}
