<?php

namespace Modules\Base\Repositories\Classes;

use Illuminate\Support\Facades\Cache;

class LaravelRepositoryClass
{
    protected $model;
    protected $cache_key;

    public function all($conditions = [], $with = [])
    {
        $query = $this->model;
        $query = $conditions != [] ? $query->where($conditions) : $query;
        $query = $with != [] ? $query->with($with) : $query;
        return $query->get();
    }


    public function paginate($query, $per_page = 15, $conditions = [], $sort_key = 'id', $sort_order = 'asc')
    {
        $query = $conditions != [] ? $query->where($conditions) : $query;
        // sort by specific key in ascending or descending
        $query = $sort_key != null ? $query->orderBy($sort_key, $sort_order) : $query;
        return $query->paginate($per_page);
    }

    public function create(array $attributes, $load = [])
    {
        $data = $this->model->create($attributes);

        return $load != []
            ? $data->load($load)
            : $data;
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

        $data = $data->firstOrFail();

        return $data;
    }

    public function update($value, $attributes = [], $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $column
            ? $data->where($column, $value)
            : $data;
        $data = $data->firstOrFail();

        $data->update($attributes);

        return $data->load($with);
    }

    public function delete($value, $conditions = [], $column = 'id')
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;
        $data = $column
            ? $data->where($column, $value)
            : $data;
        $data = $data->firstOrFail();

        return $data->delete();
    }
}
