<?php

namespace Modules\ACL\Repositories;

use Modules\ACL\Entities\Role;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;

class RoleRepository extends LaravelRepositoryClass
{
    public function __construct(Role $role)
    {
        $this->model = $role;
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
            $query = $query->orWhere(function ($q) use ($search_keys){
                $q->where('name', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('key', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('id', 'LIKE', '%'.$search_keys.'%');
            });
        }
        return $query;
    }
}
