<?php

namespace Modules\Users\Services\Common;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Users\Facades\UserCache;
use Modules\Users\Repositories\UserTypesRepository;
use Modules\Users\Transformers\AccountTypeResource;

class UserTypesService extends LaravelServiceClass
{
    private $userTypesRepository;

    public function __construct(UserTypesRepository $userTypesRepository)
    {
        $this->userTypesRepository = $userTypesRepository;
    }

    public function index()
    {
        $user_types = CacheHelper::getCache(UserCache::userType());

        if (!$user_types){
            $user_types = $this->userTypesRepository->all();
            CacheHelper::putCache(UserCache::userType(), $user_types);
        }

        $user_types = AccountTypeResource::collection($user_types);
        return ApiResponse::format(200, $user_types);
    }

}
