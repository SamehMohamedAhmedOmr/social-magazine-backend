<?php

namespace Modules\Article\Http\Controllers\Front;


use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Article\Http\Requests\Front\AddArticleInfoRequest;
use Modules\Article\Http\Requests\Front\AddArticleRequest;
use Modules\Article\Http\Requests\Front\ArticleIdRequest;
use Modules\Article\Services\Frontend\ArticleManagementService;
use Modules\Base\Requests\PaginationRequest;

class ArticleManagementController extends Controller
{

    private $service;

    public function __construct(ArticleManagementService $service)
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

    public function show(ArticleIdRequest $request)
    {
        return $this->service->show($request->id);
    }

    public function store(AddArticleRequest $request)
    {
        return $this->service->store($request);
    }

    public function updateInfo(AddArticleInfoRequest $request)
    {
        return $this->service->updateInfo($request);
    }

    public function confirm(ArticleIdRequest $request)
    {
        return $this->service->confirm($request);
    }

}
