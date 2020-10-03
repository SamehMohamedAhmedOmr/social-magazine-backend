<?php

namespace Modules\ACL\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\ACL\Services\CMS\PermissionService;

class PermissionController extends Controller
{
    private $permissionService;

    public function __construct(PermissionService $rolePermission)
    {
        $this->permissionService = $rolePermission;
    }

    /**
     * Get all Permission
     *
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->permissionService->index();
    }

    /**
     * Get all Permission
     *
     * @return JsonResponse
     */
    public function userPermissions()
    {
        return $this->permissionService->userPermissions();
    }
}
