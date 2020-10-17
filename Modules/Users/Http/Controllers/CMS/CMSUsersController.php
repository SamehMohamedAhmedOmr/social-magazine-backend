<?php

namespace Modules\Users\Http\Controllers\CMS;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Users\Http\Requests\AccountRequest;
use Modules\Users\Http\Requests\ProfileRequest;
use Modules\Users\Services\Common\UserService;

class CMSUsersController extends Controller
{
    private $userService;

    public function __construct(UserService $user)
    {
        $this->userService = $user;
    }

    // profile Method
    public function get()
    {
        return $this->userService->get(true);
    }

    public function updateProfile(ProfileRequest $profile)
    {
        return $this->userService->update(Auth()->id(), $profile, true);
    }


}
