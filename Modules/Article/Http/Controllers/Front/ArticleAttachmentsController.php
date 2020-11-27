<?php

namespace Modules\Article\Http\Controllers\Front;


use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Article\Http\Requests\Front\ArticleAttachmentsRequest;
use Modules\Article\Http\Requests\Front\ArticleIdRequest;
use Modules\Article\Services\Frontend\ArticleAttachmentsService;
use Modules\Base\Requests\PaginationRequest;
use Throwable;

class ArticleAttachmentsController extends Controller
{

    private $service;

    public function __construct(ArticleAttachmentsService $service)
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
     * @param ArticleAttachmentsRequest $request
     * @return JsonResponse|void
     * @throws Throwable
     */
    public function store(ArticleAttachmentsRequest $request)
    {
        return $this->service->store($request);
    }


    public function show(ArticleAttachmentsRequest $request)
    {
        return $this->service->show($request->article_attachment, $request);
    }


    public function update(ArticleAttachmentsRequest $request)
    {
        return $this->service->update($request->article_attachment, $request);
    }

    public function destroy(ArticleAttachmentsRequest $request)
    {
        return $this->service->delete($request->article_attachment, $request);
    }

}
