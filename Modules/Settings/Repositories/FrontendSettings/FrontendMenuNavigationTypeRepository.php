<?php

namespace Modules\Settings\Repositories\FrontendSettings;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\FrontendSettings\FrontendMenuNavigationType;

class FrontendMenuNavigationTypeRepository extends LaravelRepositoryClass
{
    public function __construct(FrontendMenuNavigationType $frontend_menu_navigation_type)
    {
        $this->model = $frontend_menu_navigation_type;
        $this->cache_key = 'frontend_menu_navigation_type';
    }

    public function paginate($per_page = 15, $conditions = null, $search_keys = null,
                             $sort_key = 'id', $sort_order = 'asc', $lang = null)
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
                $q->orWhere('name', 'LIKE', '%'.$search_keys.'%');
            });
        }
        return $query;
    }
}
