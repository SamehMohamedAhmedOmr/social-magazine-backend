<?php

namespace Modules\Article\Services\CMS;

use Carbon\Carbon;
use Modules\Article\Repositories\ArticleRepository;
use Modules\Article\Transformers\Front\ArticleResource;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\PreArticle\Facades\StatusListHelper;
use Modules\PreArticle\Repositories\ArticleFilterRepository;

class ArticleManagerListService extends LaravelServiceClass
{
    private $main_repository,
            $articleFilterRepository;

    public function __construct(ArticleRepository $repository,
                                ArticleFilterRepository $articleFilterRepository)
    {
        $this->main_repository = $repository;
        $this->articleFilterRepository = $articleFilterRepository;
    }

    public function all()
    {
        $articles = $this->main_repository->all();

        $articles->load([
            'selectedJudges'
        ]);

        $articles = $this->filterArticles($articles);

        return ApiResponse::format(200, $articles);
    }

    private function filterArticles($articles){
        $current_date = Carbon::now()->toDateString();

        $articles = $articles->where('chapter_id', null);

        $NOT_PUBLISHED =  $articles;

        $NOT_COMPLETED = collect([]);
        $NEW = collect([]);

        $SPECIALIZED_FOR_EDITOR =  collect([]);
        $DONE_BY_EDITOR =  collect([]);

        $SPECIALIZED_FOR_REFEREES =  collect([]);
        $NOT_BEEN_JUDGED_AT_TIME =  collect([]);
        $BEEN_JUDGED_FROM_ALL =  collect([]);
        $BEEN_JUDGED_FROM_SOME =  collect([]);

        $NEED_REVIEW =  collect([]);
        $BEEN_REVIEWED =  collect([]);
        $NOT_REVIEWED_AT_TIME =  collect([]);

        $FINALLY_ACCEPTED =  collect([]);
        $ACCEPTED_SCIENTIFICALLY =  collect([]);
        $WITHDRAWAL =  collect([]);

        $REJECTED =  collect([]);
        $SENT_FOR_PAYMENT =  collect([]);


        foreach ($articles as $article){
            if (isset($article->lastStatus)){
                $last_status = $article->lastStatus;
                $selected_judges = $article->selectedJudges;
                if (isset($last_status->status)){

                    $status = $last_status->status;
                    $key = $status->key;

                    switch ($key){
                        case StatusListHelper::NOT_COMPLETED():
                            $NOT_COMPLETED->push($article);
                            break;

                        case StatusListHelper::NEW():
                            $NEW->push($article);
                            break;

                        case StatusListHelper::SPECIALIZED_FOR_EDITOR():
                            if ($status->done){
                                $DONE_BY_EDITOR->push($article);
                            }
                            else{
                                $SPECIALIZED_FOR_EDITOR->push($article);
                            }
                            break;

                        case StatusListHelper::SPECIALIZED_FOR_REFEREES():
                            $SPECIALIZED_FOR_REFEREES->push($article);
                            list($check_count, $count_of_judges) = $this->count_of_judges($selected_judges);

                            if ($check_count){
                                $BEEN_JUDGED_FROM_ALL->push($article);
                            }
                            else{
                                if ($last_status->judgement_date < $current_date){
                                    $NOT_BEEN_JUDGED_AT_TIME->push($article);
                                }
                                else if ($count_of_judges){
                                    $BEEN_JUDGED_FROM_SOME->push($article);
                                }
                            }
                            break;

                        case StatusListHelper::NEED_FOR_RESENT():
                        case StatusListHelper::NEED_FOR_BIG_REVIEW():
                        case StatusListHelper::NEED_FOR_SMALL_REVIEW():
                        case StatusListHelper::ACCEPTED_WITH_NEED_FOR_SMALL_REVIEW():
                            if ($status->done){
                                $BEEN_REVIEWED->push($article);
                            }
                            else {
                                if ($last_status->review_date < $current_date){
                                    $NOT_REVIEWED_AT_TIME->push($article);
                                }
                                else{
                                    $NEED_REVIEW->push($article);
                                }
                            }
                            break;

                        case StatusListHelper::REJECTED_DUE_GOALS():
                        case StatusListHelper::REJECTED_DUE_MANY_RESENT():
                        case StatusListHelper::REJECTED_DUPLICATE():
                        case StatusListHelper::REJECTED_DUE_NO_PRIORITY():
                        case StatusListHelper::REJECTED_DUE_LITERARY_PROBLEMS():
                        case StatusListHelper::REJECTED_DUE_ALL_ARBITRATORS_REFUSED_THE_ARBITRATION():
                        case StatusListHelper::REJECTED_DUE_REFEREES_RECOMMENDATIONS_OR_EDITOR():
                        case StatusListHelper::REJECTED():
                            $REJECTED->push($article);
                            break;

                        case StatusListHelper::SENT_FOR_PAYMENT():
                            $SENT_FOR_PAYMENT->push($article);
                            break;

                        case StatusListHelper::ACCEPTED_SCIENTIFICALLY():
                            $ACCEPTED_SCIENTIFICALLY->push($article);
                            break;

                        case StatusListHelper::FINALLY_ACCEPTED():
                            $FINALLY_ACCEPTED->push($article);
                            break;

                        case StatusListHelper::WITHDRAWAL():
                            $WITHDRAWAL->push($article);
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        return [
            'NOT_PUBLISHED' => ArticleResource::collection($NOT_PUBLISHED),
            'NOT_COMPLETED' => ArticleResource::collection($NOT_COMPLETED),
            'NEW' => ArticleResource::collection($NEW),
            'SPECIALIZED_FOR_EDITOR' => ArticleResource::collection($SPECIALIZED_FOR_EDITOR),
            'DONE_BY_EDITOR' => ArticleResource::collection($DONE_BY_EDITOR),

            'SPECIALIZED_FOR_REFEREES' => ArticleResource::collection($SPECIALIZED_FOR_REFEREES),
            'NOT_BEEN_JUDGED_AT_TIME' => ArticleResource::collection($NOT_BEEN_JUDGED_AT_TIME),
            'BEEN_JUDGED_FROM_ALL' => ArticleResource::collection($BEEN_JUDGED_FROM_ALL),
            'BEEN_JUDGED_FROM_SOME' => ArticleResource::collection($BEEN_JUDGED_FROM_SOME),

            'NEED_REVIEW' => ArticleResource::collection($NEED_REVIEW),
            'BEEN_REVIEWED' => ArticleResource::collection($BEEN_REVIEWED),
            'NOT_REVIEWED_AT_TIME' => ArticleResource::collection($NOT_REVIEWED_AT_TIME),

            'FINALLY_ACCEPTED' => ArticleResource::collection($FINALLY_ACCEPTED),
            'ACCEPTED_SCIENTIFICALLY' => ArticleResource::collection($ACCEPTED_SCIENTIFICALLY),
            'WITHDRAWAL' => ArticleResource::collection($WITHDRAWAL),

            'REJECTED' => ArticleResource::collection($REJECTED),
            'SENT_FOR_PAYMENT' => ArticleResource::collection($SENT_FOR_PAYMENT),
        ];
    }

    private function count_of_judges($selected_judges){
        $count_number_of_judges = 0;
        foreach ($selected_judges as $judge){
            if (isset($judge->recommendation_id)){
                $count_number_of_judges++;
            }
        }
        return [
            count($selected_judges) == $count_number_of_judges,
            $count_number_of_judges
        ];
    }



}
