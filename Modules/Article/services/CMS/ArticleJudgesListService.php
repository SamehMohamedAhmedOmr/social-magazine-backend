<?php

namespace Modules\Article\Services\CMS;

use Modules\Article\Repositories\ArticleRepository;
use Modules\Article\Transformers\Front\ArticleResource;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\PreArticle\Facades\StatusListHelper;
use Modules\PreArticle\Repositories\ArticleFilterRepository;
use Modules\PreArticle\Repositories\ArticleStatusListRepository;

class ArticleJudgesListService extends LaravelServiceClass
{
    private $main_repository,
            $articleStatusListRepository,
            $articleFilterRepository;

    public function __construct(ArticleRepository $repository,
                                ArticleStatusListRepository $articleStatusListRepository,
                                ArticleFilterRepository $articleFilterRepository)
    {
        $this->main_repository = $repository;
        $this->articleFilterRepository = $articleFilterRepository;
        $this->articleStatusListRepository = $articleStatusListRepository;
    }

    public function all()
    {
        $article_status = $this->articleStatusListRepository->get(StatusListHelper::SPECIALIZED_FOR_REFEREES(),
            [],'key');

        $articles = $this->main_repository->getAllForJudges([
            'chapter_id' => null
        ]);

//        $articles = $this->filterArticles($articles);

        return ApiResponse::format(200, $articles);
    }

    private function filterArticles($articles){
        $SPECIALIZED_FOR_EDITOR =  collect([]);
        $DONE_BY_EDITOR =  collect([]);

        foreach ($articles as $article){
            if (isset($article->lastStatus)){
                $last_status = $article->lastStatus;
                if (isset($last_status->status)){

                    $status = $last_status->status;
                    $key = $status->key;

                    switch ($key){
                        case StatusListHelper::SPECIALIZED_FOR_REFEREES():
                            if ($status->done){
                                $DONE_BY_EDITOR->push($article);
                            }
                            else{
                                $SPECIALIZED_FOR_EDITOR->push($article);
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        return [
            'SPECIALIZED_FOR_EDITOR' => ArticleResource::collection($SPECIALIZED_FOR_EDITOR),
            'DONE_BY_EDITOR' => ArticleResource::collection($DONE_BY_EDITOR),
        ];
    }


}
