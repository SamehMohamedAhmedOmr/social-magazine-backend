<?php

namespace Modules\Users\Http\Controllers\Frontend;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\LoginRequest;
use Modules\Users\Http\Requests\PasswordForgetRequest;
use Modules\Users\Http\Requests\PasswordRequest;
use Modules\Users\Http\Requests\PasswordResetRequest;
use Modules\Users\Http\Requests\RegisterRequest;
use Modules\Users\Services\Common\AuthenticationService;

class UserAuthenticationController extends Controller
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
        return $this->authService->loginAsUser();
    }

    /**
     * Register api
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function register(RegisterRequest $request)
    {
        return $this->authService->register();
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
     */
    public function forgetChangePassword(PasswordResetRequest $request)
    {
        return $this->authService->forgetChangePassword();
    }
}
