<?php

namespace Modules\Users\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Users\Http\Requests\CMSUsersRequest;
use Modules\Users\Http\Requests\ProfileRequest;
use Modules\Users\Services\CMS\CMSUsersService;
use Modules\Users\Services\Common\UserService;

class CMSUsersController extends Controller
{
    private $userService;
    private $cms_users_service;

    public function __construct(UserService $user, CMSUsersService $admin)
    {
        $this->userService = $user;
        $this->cms_users_service = $admin;
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
     * Get all CMSUser
     *
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function index(PaginationRequest $request)
    {
        return $this->cms_users_service->index();
    }

    /**
     * Handles Add New CMSUser
     *
     * @param CMSUsersRequest $request
     * @return JsonResponse
     */
    public function store(CMSUsersRequest $request)
    {
        return $this->cms_users_service->store($request);
    }

    public function show(CMSUsersRequest $request)
    {
        return $this->cms_users_service->show($request->admin);
    }

    public function update(CMSUsersRequest $request)
    {
        return $this->cms_users_service->update($request->admin, $request);
    }

    public function destroy(CMSUsersRequest $request)
    {
        return $this->cms_users_service->delete($request->admin);
    }

    /**
     *
     * @param PaginationRequest $request
     * @return JsonResponse
     */
    public function export(PaginationRequest $request)
    {
        return $this->cms_users_service->export();
    }
}
