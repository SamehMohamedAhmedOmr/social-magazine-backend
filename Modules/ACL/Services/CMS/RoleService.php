<?php

namespace Modules\ACL\Services\CMS;

use Modules\ACL\ExcelExports\RoleExport;
use Modules\ACL\Facade\PermissionHelper;
use Modules\Base\Facade\ExcelExportHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\ACL\Repositories\PermissionRepository;
use Modules\ACL\Repositories\RoleRepository;
use Modules\Users\Repositories\UserRepository;
use Modules\ACL\Transformers\RoleResource;

class RoleService extends LaravelServiceClass
{
    private $role_repo;
    private $user_repo;
    private $permission_repo;

    public function __construct(RoleRepository $role, UserRepository $user, PermissionRepository $permission)
    {
        $this->role_repo = $role;
        $this->user_repo = $user;
        $this->permission_repo = $permission;
    }

    public function index()
    {
        if (request('is_pagination')) {
            list($roles, $pagination) = parent::paginate($this->role_repo, null, false);
        } else {
            $roles = parent::list($this->role_repo, false);
            $pagination = null;
        }

        $roles->load('permissions');

        $roles = RoleResource::collection($roles);
        return ApiResponse::format(200, $roles, [], $pagination);
    }

    public function store($request = null)
    {
        $key = str_replace(' ', '_', $request->name);
        $role =  $this->role_repo->create([
            'name' => $request->name,
            'key' => strtoupper($key)
        ]);

        $role->syncPermissions($request->permissions);

        $role->load('permissions');

        $role = RoleResource::make($role);
        return ApiResponse::format(201, $role, 'Role Added!');
    }

    public function show($id)
    {
        $role = $this->role_repo->get($id);
        $role->load('permissions');
        $role = RoleResource::make($role);
        return ApiResponse::format(201, $role);
    }

    public function update($id, $request = null)
    {
        $key = str_replace(' ', '_', $request->name);

        $role = $this->role_repo->update($id, [
            'name' => $request->name,
            'key' => strtoupper($key)
        ]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        $role->load('permissions');
        $role = RoleResource::make($role);
        return ApiResponse::format(201, $role);
    }

    public function delete($id)
    {
        $role = $this->role_repo->get($id);
        $users = $role->users;
        if (count($users)) {
            PermissionHelper::notAbleToDeleteRole();
        }
        $role = $this->role_repo->delete($id);
        return ApiResponse::format(200, $role, 'Role Deleted!');
    }

    public function assignRoleToUser($request)
    {
        $role = $this->role_repo->get($request->role_key, [], 'key');

        $user_id = $request->user_id;

        $role->users()->detach($user_id);
        $role->users()->attach($user_id);

        return ApiResponse::format(201, null, 'Role Assigned Successfully');
    }

    public function revokeRoleToUser($request)
    {
        $role = $this->role_repo->get($request->role_key, [], 'key');

        $role->users()->detach($request->user_id);

        return ApiResponse::format(201, null, 'Role Revoked from user Successfully');
    }

    public function addRoleWithPermissions($request)
    {
        $role =  $this->role_repo->create([
            'name' => $request->name,
            'key' => $request->key
        ]);

        $role->syncPermissions($request->permissions);

        $role->load('permissions');

        $role = RoleResource::make($role);

        return ApiResponse::format(201, $role, 'Role with Permission Added Successfully');
    }

    public function assignPermissionsRole($request)
    {
        $role = $this->role_repo->get($request->role_key, [], 'key');

        $role->syncPermissions(request()->permissions);

        $role->load('permissions');

        $role = RoleResource::make($role);

        return ApiResponse::format(201, $role, 'Permissions Assigned to Role Successfully');
    }

    public function export(){
        $file_path = ExcelExportHelper::export('Roles', \App::make(RoleExport::class));

        return ApiResponse::format(200, $file_path);
    }
}
