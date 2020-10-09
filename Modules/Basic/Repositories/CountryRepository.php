<?php

namespace Modules\Basic\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Basic\Entities\Country;

class CountryRepository extends LaravelRepositoryClass
{
    public function __construct(Country $country)
    {
        $this->model = $country;
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
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

}
