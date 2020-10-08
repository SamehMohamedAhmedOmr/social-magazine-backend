<?php

namespace Modules\Users\Http\Controllers\CMS;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\LoginRequest;
use Modules\Users\Http\Requests\PasswordForgetRequest;
use Modules\Users\Http\Requests\PasswordRequest;
use Modules\Users\Http\Requests\PasswordResetRequest;
use Modules\Users\Services\Common\AuthenticationService;

class AdminAuthenticationController extends Controller
{
    private $authService;

    public function __construct(AuthenticationService $auth)
    {
        $this->authService = $auth;
    }

    /**
     * Handles user login
     *
     * @param loginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return $this->authService->loginForCMS();
    }

    public function logout()
    {
        return $this->authService->logout();
    }

    /**
     * Handles Reset Password
     *
     * @param PasswordRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function resetPassword(PasswordRequest $request)
    {
        return $this->authService->resetPassword();
    }

    public function forgetPassword(PasswordForgetRequest $request)
    {
        return $this->authService->forgetPassword();
    }

    /**
     * Reset password
     *
     * @param PasswordResetRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function forgetChangePassword(PasswordResetRequest $request)
    {
        return $this->authService->forgetChangePassword();
    }
}
