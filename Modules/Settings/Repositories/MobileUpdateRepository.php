<?php

namespace Modules\Settings\Repositories;

use Illuminate\Support\Facades\Cache;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\MobileUpdate;

class MobileUpdateRepository extends LaravelRepositoryClass
{
    public function __construct(MobileUpdate $mobile_update)
    {
        $this->model = $mobile_update;
        $this->cache_key = 'mobile_update';
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
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
            $query = $query->orWhere(function ($q) use ($search_keys){
                $q->where('device_type', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('build_number', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('id', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('release_date', 'LIKE', '%'.$search_keys.'%');
            });
        }
        return $query;
    }

    public function getLastUpdate($value, $column = 'id')
    {
        $data = $column
            ? $this->model->where($column, $value)
            : $this->model;

        $data = $data->latest('release_date')->first();

        return $data;
    }
}
