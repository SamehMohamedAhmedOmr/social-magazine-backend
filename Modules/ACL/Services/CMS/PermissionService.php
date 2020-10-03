<?php

namespace Modules\ACL\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\ACL\Repositories\PermissionRepository;
use Modules\ACL\Transformers\PermissionResource;

class PermissionService extends LaravelServiceClass
{
    private $permission_repo;

    public function __construct(PermissionRepository $permission)
    {
        $this->permission_repo = $permission;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($permissions, $pagination) = parent::paginate($this->permission_repo,
                null, false);
        } else {
            $permissions = $this->permission_repo->all();
            $pagination = null;
        }

        $permissions = PermissionResource::collection($permissions);
        return ApiResponse::format(200, $permissions, [], $pagination);
    }


    public function userPermissions()
    {
        $user = \Auth::user();
        $roles = $user->roles;

        $permissions = collect([]);

        foreach ($roles as $role){
            foreach ($role->permissions as $permission){
                $permissions->push($permission);
            }
        }

        $permissions = $permissions->unique();

        $permissions = PermissionResource::collection($permissions);

        return ApiResponse::format(200, $permissions, []);
    }
}
