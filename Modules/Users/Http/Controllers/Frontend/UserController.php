<?php

namespace Modules\Users\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Base\Requests\PaginationRequest;
use Modules\Users\Http\Requests\ProfileRequest;
use Modules\Users\Services\Common\UserService;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $user)
    {
        $this->userService = $user;
    }

    public function show()
    {
        return $this->userService->get();
    }

    public function updateProfile(ProfileRequest $profile)
    {
        return $this->userService->update(Auth()->id(), $profile);
    }

    public function userSummary(PaginationRequest $request)
    {
        return $this->userService->userSummary();
    }
}
