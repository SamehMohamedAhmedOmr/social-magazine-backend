<?php

namespace Modules\Settings\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\Fonts;

class FontsRepository extends LaravelRepositoryClass
{
    public function __construct(Fonts $fonts)
    {
        $this->model = $fonts;
        $this->cache_key = 'fonts';
    }

    public function paginate($per_page = 15, $conditions = null, $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
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
                $q->where('id', 'LIKE', '%'.$search_keys.'%');
                $q->orWhere('font_name', 'LIKE', '%'.$search_keys.'%');
            });
        }
        return $query;
    }

}
