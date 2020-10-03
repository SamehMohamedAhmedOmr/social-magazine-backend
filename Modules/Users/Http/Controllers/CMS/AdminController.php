<?php

namespace Modules\Users\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Users\Http\Requests\AdminRequest;
use Modules\Users\Http\Requests\CMS\ClientProductFilterRequest;
use Modules\Users\Http\Requests\ProfileRequest;
use Modules\Users\Services\CMS\AdminService;
use Modules\Users\Services\Common\UserService;

class AdminController extends Controller
{
    private $userService;
    private $admin_service;

    public function __construct(UserService $user, AdminService $admin)
    {
        $this->userService = $user;
        $this->admin_service = $admin;
    }

    // profile Method
    public function get()
    {
        return $this->userService->get();
    }

    public function updateProfile(ProfileRequest $profile)
    {
        return $this->userService->update(Auth()->id());
    }

    // API RESOURCE METHODS
    /**
     * Get all Admin
     *
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->admin_service->index();
    }

    /**
     * Handles Add New Admin
     *
     * @param AdminRequest $request
     * @return JsonResponse
     */
    public function store(AdminRequest $request)
    {
        return $this->admin_service->store($request);
    }

    public function show(AdminRequest $request)
    {
        return $this->admin_service->show($request->admin);
    }

    public function update(AdminRequest $request)
    {
        return $this->admin_service->update($request->admin, $request);
    }

    public function destroy(AdminRequest $request)
    {
        return $this->admin_service->delete($request->admin);
    }

    /**
     *
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function export(PaginationRequest $request)
    {
        return $this->admin_service->export();
    }
}
