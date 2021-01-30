<?php

namespace Modules\Article\Http\Controllers\Front;


use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Article\Http\Requests\Front\ArticleAuthorsRequest;
use Modules\Article\Http\Requests\Front\ArticleIdRequest;
use Modules\Article\Services\Frontend\ArticleAuthorsService;
use Modules\Base\Requests\PaginationRequest;
use Throwable;

class ArticleAuthorsController extends Controller
{

    private $service;

    public function __construct(ArticleAuthorsService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @param PaginationRequest $request
     * @param ArticleIdRequest $articleIdRequest
     * @return JsonResponse|void
     */
    public function index(PaginationRequest $request, ArticleIdRequest $articleIdRequest)
    {
        return $this->service->index($articleIdRequest);
    }


    /**
     * store resource.
     * @param ArticleAuthorsRequest $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function store(ArticleAuthorsRequest $request)
    {
        return $this->service->store($request);
    }


    public function show(ArticleAuthorsRequest $request)
    {
        return $this->service->show($request->article_author, $request);
    }


    public function update(ArticleAuthorsRequest $request)
    {
        return $this->service->update($request->article_author, $request);
    }

    public function destroy(ArticleAuthorsRequest $request)
    {
        return $this->service->delete($request->article_author, $request);
    }


}
