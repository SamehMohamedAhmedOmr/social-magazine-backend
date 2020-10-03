<?php

namespace Modules\ACL\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Base\Requests\PaginationRequest;
use Modules\ACL\Http\Requests\RolePermissionRequest;
use Modules\ACL\Http\Requests\RoleRequest;
use Modules\ACL\Http\Requests\UserRoleRequest;
use Modules\ACL\Services\CMS\RoleService;

class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $rolePermission)
    {
        $this->roleService = $rolePermission;
    }

    /**
     * Get all Roles
     *
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->roleService->index();
    }

    /**
     * Handles Add New Role
     *
     * @param RoleRequest $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request)
    {
        return $this->roleService->store($request);
    }

    public function show(RoleRequest $request)
    {
        return $this->roleService->show($request->role);
    }

    public function update(RoleRequest $request)
    {
        return $this->roleService->update($request->role, $request);
    }

    public function destroy(RoleRequest $request)
    {
        return $this->roleService->delete($request->role);
    }

    public function assignRoleToUser(UserRoleRequest $request)
    {
        return $this->roleService->assignRoleToUser($request);
    }

    public function revokeRoleToUser(UserRoleRequest $request)
    {
        return $this->roleService->revokeRoleToUser($request);
    }

    public function addRoleWithPermissions(RolePermissionRequest $request)
    {
        return $this->roleService->addRoleWithPermissions($request);
    }

    public function assignPermissionsRole(RolePermissionRequest $request)
    {
        return $this->roleService->assignPermissionsRole($request);
    }

    public function export(PaginationRequest $request)
    {
        return $this->roleService->export();
    }
}
