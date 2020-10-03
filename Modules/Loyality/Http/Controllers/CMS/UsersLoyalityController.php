<?php

namespace Modules\Loyality\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Modules\Loyality\Http\Requests\CMS\AddPointsUserRequest;
use Modules\Loyality\Http\Requests\CMS\RemovePointsUserRequest;
use Modules\Loyality\Services\CMS\LoyalityUserService;

class UsersLoyalityController extends Controller
{
    protected $loyalityUserService;

    public function __construct(LoyalityUserService $loyalityUserService)
    {
        $this->loyalityUserService = $loyalityUserService;
    }

    public function add(AddPointsUserRequest $addPointsUserRequest)
    {
        return $this->loyalityUserService->addPoints($addPointsUserRequest);
    }

    public function remove(RemovePointsUserRequest $removePointsUserRequest)
    {
        return $this->loyalityUserService->removePoints($removePointsUserRequest);
    }
}
