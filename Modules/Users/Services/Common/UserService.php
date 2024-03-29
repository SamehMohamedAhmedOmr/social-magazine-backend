<?php

namespace Modules\Users\Services\Common;

use Illuminate\Support\Facades\Auth;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Users\Facades\UsersHelper;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Transformers\CMS\ProfileResource;
use Modules\Users\Transformers\FRONT\ProfileResource as FrontendProfileResource;

class UserService extends LaravelServiceClass
{
    private $user_repo;

    public function __construct(UserRepository $user)
    {
        $this->user_repo = $user;
    }

    public function get($cms = true)
    {
        $user = Auth::user();

        $user = $this->getUserResource($user, $cms);

        return ApiResponse::format(201, $user);
    }

    public function update($id, $request = null, $cms = true)
    {
        $request_data = UsersHelper::prepareUpdateProfile($request->all());
        $user = $this->user_repo->update($id, $request_data);

        $user = $this->getUserResource($user, $cms);

        return ApiResponse::format(201, $user);
    }

    private function getUserResource($user, $cms = true)
    {
        if (!$cms) {
            $user->load([
                'gender',
                'title',
                'educationalLevel',
                'educationalDegree',
                'country',
            ]);
            return FrontendProfileResource::make($user);
        }

        $user->load([
            'accountTypes'
        ]);
        return ProfileResource::make($user);
    }
}
