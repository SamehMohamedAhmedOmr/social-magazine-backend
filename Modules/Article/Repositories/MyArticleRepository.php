<?php

namespace Modules\Article\Repositories;

use Modules\Article\Entities\Article;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;

class MyArticleRepository extends LaravelRepositoryClass
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

        $status_key = request('status');

        if ($status_key){
            $query = $query->with(['lastStatus.status']);

            $query = $query->whereHas('lastStatus', function ($inner_query) use ($status_key) {
                $inner_query->whereHas('status', function ($q) use ($status_key) {
                    $q->where('key', $status_key);
                });
            });
        }

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
}
