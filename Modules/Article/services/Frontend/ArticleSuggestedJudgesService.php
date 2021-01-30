<?php

namespace Modules\Article\Services\Frontend;

use Illuminate\Http\JsonResponse;
use Modules\Article\Repositories\ArticleSuggestedJudgeRepository;
use Modules\Article\Transformers\Front\ArticleSuggestedJudgesResource;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Throwable;

class ArticleSuggestedJudgesService extends LaravelServiceClass
{
    private $main_repository;

    public function __construct(ArticleSuggestedJudgeRepository $repository)
    {
        $this->main_repository = $repository;
    }

    public function index($request = null)
    {
        $pagination = null;

        $contents = parent::list($this->main_repository, true,[
            'article_id' => $request->id
        ]);

        $contents = ArticleSuggestedJudgesResource::collection($contents);
        return ApiResponse::format(200, $contents, null, $pagination);
    }

    /**
     * Handles Add New CMSUser
     *
     * @param null $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store($request = null)
    {
        return \DB::transaction(function () use ($request) {

            $content =  $this->main_repository->create($request->all());

            $content = ArticleSuggestedJudgesResource::make($content);

            return ApiResponse::format(201, $content, 'Content Created!');
        });

    }

    public function show($id, $request = null)
    {
        $content = $this->main_repository->get($id,[
            'article_id' => $request->article_id
        ]);

        $content = ArticleSuggestedJudgesResource::make($content);

        return ApiResponse::format(200, $content);
    }

    public function update($id, $request = null)
    {
        $content = $this->main_repository->update($id, $request->all(),[
            'article_id' => $request->article_id
        ]);

        $content = ArticleSuggestedJudgesResource::make($content);

        return ApiResponse::format(200, $content,'Content Updated');
    }

    public function delete($id, $request = null)
    {
        $content = $this->main_repository->delete($id,[
            'article_id' => $request->article_id
        ]);
        return ApiResponse::format(200, $content, 'Content Deleted!');
    }

}
