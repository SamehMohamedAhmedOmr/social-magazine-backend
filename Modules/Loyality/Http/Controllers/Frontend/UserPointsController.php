<?php

namespace Modules\Loyality\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Loyality\Services\Common\UserPointsService;

class UserPointsController extends Controller
{
    private $user_points_service;

    public function __construct(UserPointsService $user_points_service)
    {
        $this->user_points_service = $user_points_service;
    }

    public function show(Request $request)
    {
        return $this->user_points_service->get(Auth::id());
    }

    public function log(Request $request)
    {
        return $this->user_points_service->log(Auth::id());
    }
}
