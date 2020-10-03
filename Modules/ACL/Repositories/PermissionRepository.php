<?php

namespace Modules\ACL\Repositories;

use Modules\ACL\Entities\Permissions;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;

class PermissionRepository extends LaravelRepositoryClass
{
    public function __construct(Permissions $permission)
    {
        $this->model = $permission;
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->model;

        if ($search_keys) {
            $query = $query->where('name', 'LIKE', '%'.$search_keys.'%');
        }

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }
}
