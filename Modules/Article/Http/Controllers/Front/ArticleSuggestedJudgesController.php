<?php

namespace Modules\Article\Http\Controllers\Front;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Article\Http\Requests\Front\ArticleIdRequest;
use Modules\Article\Http\Requests\Front\ArticleSuggestedJudgesRequest;
use Modules\Article\Services\Frontend\ArticleSuggestedJudgesService;
use Modules\Base\Requests\PaginationRequest;
use Throwable;

class ArticleSuggestedJudgesController extends Controller
{

    private $service;

    public function __construct(ArticleSuggestedJudgesService $service)
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
     * @param ArticleSuggestedJudgesRequest $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function store(ArticleSuggestedJudgesRequest $request)
    {
        return $this->service->store($request);
    }


    public function show(ArticleSuggestedJudgesRequest $request)
    {
        return $this->service->show($request->article_judge, $request);
    }


    public function update(ArticleSuggestedJudgesRequest $request)
    {
        return $this->service->update($request->article_judge, $request);
    }

    public function destroy(ArticleSuggestedJudgesRequest $request)
    {
        return $this->service->delete($request->article_judge, $request);
    }


}
