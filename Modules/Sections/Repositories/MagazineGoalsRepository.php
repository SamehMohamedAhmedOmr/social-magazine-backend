<?php

namespace Modules\Sections\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Sections\Entities\MagazineGoals;

class MagazineGoalsRepository extends LaravelRepositoryClass
{
    public function __construct(MagazineGoals $magazineGoals)
    {
        $this->model = $magazineGoals;
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

                $q->where('content', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('id', 'LIKE', '%'.$search_keys.'%');
            });
        }

        return $query;
    }

}
