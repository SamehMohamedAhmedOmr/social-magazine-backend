<?php

namespace Modules\Sections\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Sections\Entities\MagazineNews;

class MagazineNewsRepository extends LaravelRepositoryClass
{
    public function __construct(MagazineNews $model)
    {
        $this->model = $model;
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->filtering($search_keys);

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function all($conditions = [], $search_keys = [], $limit = null)
    {
        $query = $this->filtering($search_keys, $limit);

        return $query->where($conditions)->get();
    }

    private function filtering($search_keys, $limit = null ){
        $query = $this->model;

        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){

                $q->where('title', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('id', 'LIKE', '%'.$search_keys.'%');
            });
        }

        if ($limit){
            $query = $query->take($limit)->orderBy('created_at','desc');
        }

        return $query;
    }

    public function attach($model, $images)
    {
        $model->images()->detach();
        $model->images()->attach($images);
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

    public function updateVisitors($content){
        $content->views = $content->views + 1;
        $content->save();

        return $content;
    }

    public function orderByViews($conditions = [], $limit = null ){
        $query = $this->model;

        if ($limit){
            $query = $query->take($limit)->orderBy('views','desc');
        }

        return $query->where($conditions)->get();
    }


    public function numberOfActive(){
        $query = $this->model;

        return $query->where('is_active', 1)->count();
    }

}
