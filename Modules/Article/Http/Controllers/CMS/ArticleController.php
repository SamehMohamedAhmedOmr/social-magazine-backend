<?php

namespace Modules\Article\Http\Controllers\CMS;

use Illuminate\Routing\Controller;
use Modules\Article\Services\CMS\ArticleEditorListService;
use Modules\Article\Services\CMS\ArticleJudgesListService;
use Modules\Article\Services\CMS\ArticleManagerListService;

class ArticleController extends Controller
{
    private $articleManagerListService,
            $articleJudgesListService,
            $articleEditorListService;

    public function __construct(ArticleManagerListService $articleManagerListService,
                                ArticleEditorListService $articleEditorListService,
                                ArticleJudgesListService $articleJudgesListService){
        $this->articleManagerListService = $articleManagerListService;
        $this->articleEditorListService = $articleEditorListService;
        $this->articleJudgesListService = $articleJudgesListService;
    }


    public function articleForManager()
    {
        return $this->articleManagerListService->all();
    }


    public function articleForEditor()
    {
        return $this->articleEditorListService->all();
    }

    public function articleForJudges()
    {
        return $this->articleJudgesListService->all();
    }
}
