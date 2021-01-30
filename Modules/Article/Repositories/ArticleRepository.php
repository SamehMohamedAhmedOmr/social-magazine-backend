<?php

namespace Modules\Article\Repositories;

use Modules\Article\Entities\Article;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;

class ArticleRepository extends LaravelRepositoryClass
{
    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = null, $sort_key = 'id',
                             $sort_order = 'asc', $lang = null)
    {
        $query = $this->filtering($search_keys);

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function all($conditions = [], $search_keys = null)
    {
        $query = $this->filtering($search_keys);

        return $query->where($conditions)->get();
    }

    private function filtering($search_keys){
        $query = $this->model;


        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){

                $q->where('name', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('id', 'LIKE', '%'.$search_keys.'%');
            });
        }
        return $query;
    }

    public function getBySlug($value, $column = 'id', $conditions = [])
    {
        $data = $column
            ? $this->model->where($column, $value)
            : $this->model;

        $data = $conditions != []
            ? $data->where($conditions)
            : $data;

        $data = $data->first();

        return $data;
    }

    public function relationships($article, $optional = []){

        $default = [];

        $relations = array_merge($default, $optional);

        return count($relations) ? $article->load($relations) : $article;
    }

    public function getAllForEditor($article_status_id ,$conditions = [], $search_keys = null)
    {
        $query = $this->filteringForEditor($article_status_id, $search_keys);

        return $query->where($conditions)->get();
    }

    private function filteringForEditor($article_status_id , $search_keys){
        $query = $this->model;

        $query = $query->where(function ($q) use ($article_status_id) {

            $q->whereHas('lastStatus', function ($inner_query) use ($article_status_id) {
                $inner_query->where('status_id', $article_status_id)
                    ->where('magazine_director_id', \Auth::id());
            });

        });

        return $query;
    }


    public function getAllForJudges($conditions = [], $search_keys = null)
    {
        $query = $this->filteringForJudge($search_keys);

        return $query->where($conditions)->get();
    }

    private function filteringForJudge($search_keys){
        $query = $this->model;

        $query = $query->with([
            'selectedJudges',
        ]);

        $query = $query->where(function ($q) {

            $q->whereHas('selectedJudges', function ($inner_query)  {
                $inner_query->where('judge_id', \Auth::id());
            });

        });

        return $query;
    }

}
