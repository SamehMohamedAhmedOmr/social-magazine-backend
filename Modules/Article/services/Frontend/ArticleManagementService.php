<?php

namespace Modules\Article\Services\Frontend;

use Carbon\Carbon;
use Modules\Article\Facade\ArticleHelper;
use Modules\Article\Repositories\ArticleRepository;
use Modules\Article\Repositories\ArticleStatusRepository;
use Modules\Article\Transformers\Front\ArticleResource;
use Modules\Base\Facade\UtilitiesHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\PreArticle\Facades\StatusListHelper;
use Modules\PreArticle\Repositories\ArticleStatusListRepository;

class ArticleManagementService extends LaravelServiceClass
{
    private $main_repository, $articleStatusRepository, $articleStatusListRepository;

    public function __construct(ArticleRepository $repository,
                                ArticleStatusListRepository $articleStatusListRepository,
                                ArticleStatusRepository $articleStatusRepository)
    {
        $this->main_repository = $repository;
        $this->articleStatusRepository = $articleStatusRepository;
        $this->articleStatusListRepository = $articleStatusListRepository;
    }

    public function index()
    {
        $pagination = null;

        $contents = parent::list($this->main_repository, true,[
            'author_id' => \Auth::id()
        ]);

        $contents = ArticleResource::collection($contents);
        return ApiResponse::format(200, $contents, null, $pagination);
    }

    public function show($id, $request = null)
    {
        $content = $this->main_repository->get($id,[
            'author_id' => \Auth::id()
        ]);

        $content = ArticleResource::make($content);

        return ApiResponse::format(200, $content);
    }

    public function store($request = null)
    {
        return \DB::transaction(function () use ($request) {

            $data = $request->all();
            $data['author_id'] = \Auth::id();

            $slug = UtilitiesHelper::generateSlug($data['title_ar']);
            $data['slug'] = $slug;

            $article = $this->main_repository->getBySlug($slug,'slug');

            if ($article){
                ArticleHelper::duplicateNewsTitle();
            }

            $data['article_code'] = $this->generateArticleCode();

            $target_status = $this->articleStatusListRepository->get(StatusListHelper::NOT_COMPLETED(),[],'key');

            $article =  $this->main_repository->create($data);

            $this->articleStatusRepository->create([
                'article_id' => $article->id,
                'status_id' => $target_status->id
            ]);


            $article = ArticleResource::make($article);
            return ApiResponse::format(201, $article, 'Content Created!');
        });
    }

    public function updateInfo($request = null)
    {
        return \DB::transaction(function () use ($request) {

            $data = $request->all();

            if (isset($data['title_ar'])){
                $slug = UtilitiesHelper::generateSlug($data['title_ar']);
                $data['slug'] = $slug;

                $article = $this->main_repository->getBySlug($slug,'slug',[
                    [
                        'id' , '!=' , $request->article_id
                    ]
                ]);

                if ($article){
                    ArticleHelper::duplicateNewsTitle();
                }
            }

            $article =  $this->main_repository->update($request->article_id,$data);

            $article = ArticleResource::make($article);
            return ApiResponse::format(201, $article, 'Content Created!');
        });
    }

    private function generateArticleCode(){
        $uniqId = uniqid(mt_rand(), true) . Carbon::now();
        $uniqId = urlencode(sha1($uniqId));
        $uniqId = substr($uniqId,0,10);
        $uniqId = strtoupper($uniqId);

        $unique_date = substr(strtoupper(urlencode(sha1(Carbon::now()))),0,11);

        return 'ARTICLE-'.$unique_date.'-'.$uniqId;
    }

    public function confirm($request = null)
    {
        return \DB::transaction(function () use ($request) {

            $article = $this->main_repository->get($request->id);

            $target_status = $this->articleStatusListRepository->get(StatusListHelper::NEW(),[],'key');

            $status = $this->articleStatusRepository->get($article->id,[
                'status_id' => $target_status->id
            ],'article_id');

            if (!$status){
                $this->articleStatusRepository->create([
                    'article_id' => $article->id,
                    'status_id' => $target_status->id
                ]);
            }

            $article = ArticleResource::make($article);
            return ApiResponse::format(201, $article, 'Content Created!');
        });
    }

}
