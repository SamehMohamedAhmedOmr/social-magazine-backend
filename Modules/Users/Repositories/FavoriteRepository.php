<?php

namespace Modules\Users\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Users\Entities\Favorite;

class FavoriteRepository extends LaravelRepositoryClass
{
    public function __construct(Favorite $favorite)
    {
        $this->model = $favorite;
    }

    public function updateOrCreate($optional_array, $required_array)
    {
        return $this->model->updateOrCreate($optional_array, $required_array);
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        return parent::paginate($this->model, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $with != []
            ? $data->with($with)
            : $data;

        $data = $column
            ? $data->where($column, $value)
            : $data;

        $data = $data->first();

        return $data;
    }

    public function deleteItem($model)
    {
        return $model->delete();
    }
}
