<?php

namespace Modules\Article\Http\Controllers\Front;


use Illuminate\Routing\Controller;
use Modules\Article\Services\Frontend\ArticleAttachmentsService;

class ArticleAttachmentsController extends Controller
{

    private $service;

    public function __construct(ArticleAttachmentsService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('article::index');
    }


}
