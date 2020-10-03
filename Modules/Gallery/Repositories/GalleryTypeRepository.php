<?php

namespace Modules\Gallery\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Gallery\Entities\GalleryType;

class GalleryTypeRepository extends LaravelRepositoryClass
{
    public function __construct(GalleryType $galleryType)
    {
        $this->model = $galleryType;
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


    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        return parent::paginate($this->model, $per_page, $conditions, $sort_key, $sort_order);
    }
}
