<?php

namespace Modules\Article\Services\Frontend;

use Illuminate\Http\JsonResponse;
use Modules\Article\Repositories\ArticleAttachmentRepository;
use Modules\Article\Transformers\Front\ArticleAttachmentsResource;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\PreArticle\Facades\StatusListHelper;
use Modules\PreArticle\Repositories\ArticleStatusListRepository;
use Throwable;

class ArticleAttachmentsService extends LaravelServiceClass
{
    private $main_repository,$articleStatusListRepository;

    public function __construct(ArticleAttachmentRepository $repository,
                                ArticleStatusListRepository $articleStatusListRepository)
    {
        $this->main_repository = $repository;
        $this->articleStatusListRepository = $articleStatusListRepository;
    }

    public function index($request = null)
    {
        $pagination = null;

        $contents = parent::list($this->main_repository, true,[
            'article_id' => $request->id
        ]);

        $contents = ArticleAttachmentsResource::collection($contents);
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

            $data = $request->all();

            $data['uploaded_by'] = \Auth::id();

            $target_status = $this->articleStatusListRepository->get(StatusListHelper::NEW(),[],'key');

            $data['status_id'] = $target_status->id;

            $title = $request->file('file')->getClientOriginalName();

            $file_path = 'public/files/'. $request->article_id;

            $path = \Storage::putFileAs($file_path, $request->file, str_replace(' ', '-', $title));

            $path =  explode('/', $path);

            $file_name = $path[count($path)-1];

            $data['file'] = $file_name;

            $data['title'] = $title;

            $content =  $this->main_repository->create($data);

            $content = ArticleAttachmentsResource::make($content);

            return ApiResponse::format(201, $content, 'Content Created!');
        });

    }

    public function show($id, $request = null)
    {
        $content = $this->main_repository->get($id,[
            'article_id' => $request->article_id
        ]);

        $content = ArticleAttachmentsResource::make($content);

        return ApiResponse::format(200, $content);
    }

    public function update($id, $request = null)
    {
        return ApiResponse::format(200, null,'Content Updated');
    }

    public function delete($id, $request = null)
    {
        $content = $this->main_repository->delete($id,[
            'article_id' => $request->article_id
        ]);
        return ApiResponse::format(200, $content, 'Content Deleted!');
    }

}
