<?php

namespace Modules\Users\Http\Controllers\Frontend;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\SocialRequest;
use Modules\Users\Services\Frontend\SocialAuthenticationService;

class SocialAuthenticationController extends Controller
{
    private $socialAuthService;

    public function __construct(SocialAuthenticationService $social)
    {
        $this->socialAuthService = $social;
    }

    /**
     * Display a listing of the resource.
     * @param SocialRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function facebookLogin(SocialRequest $request)
    {
        return $this->socialAuthService->facebookAuth();
    }
}
