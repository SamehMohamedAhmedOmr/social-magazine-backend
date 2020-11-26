<?php

namespace Modules\Article\Http\Controllers\Front;


use Illuminate\Routing\Controller;
use Modules\Article\Http\Requests\Front\AddArticleInfoRequest;
use Modules\Article\Http\Requests\Front\AddArticleRequest;
use Modules\Article\Services\Frontend\ArticleManagementService;

class ArticleManagementController extends Controller
{

    private $service;

    public function __construct(ArticleManagementService $service)
    {
        $this->service = $service;
    }

    public function store(AddArticleRequest $request)
    {
        return $this->service->store($request);
    }

    public function updateInfo(AddArticleInfoRequest $request)
    {
        return $this->service->updateInfo($request);
    }

}
