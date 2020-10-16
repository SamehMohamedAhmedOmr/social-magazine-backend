<?php

namespace Modules\Sections\Services\CMS;

use Illuminate\Http\JsonResponse;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Sections\Repositories\WhoIsUsRepository;
use Modules\Users\Facades\UsersHelper;
use Modules\Users\Facades\UsersTypesHelper;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Transformers\CMS\AccountResource;
use Throwable;

class WhoIsUsService extends LaravelServiceClass
{
    private $user_repo;

    public function __construct(WhoIsUsRepository $user_repo)
    {
        $this->user_repo = $user_repo;
    }

    public function index()
    {
        $pagination = null;
        if (request('is_pagination')) {
            list($users, $pagination) = parent::paginate($this->user_repo, null, true);
        } else {
            $users = parent::list($this->user_repo, true);
        }

        $users->load($this->user_repo->relationships());

        $users = AccountResource::collection($users);
        return ApiResponse::format(200, $users, null, $pagination);
    }

    /**
     * Handles Add New CMSUser
     *
     * @param null $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store($request = null)
    {
        return \DB::transaction(function () use ($request) {
            $request_data = UsersHelper::prepareCreateAccount($request->all());

            $request_data['password'] = bcrypt($request_data['password']);

            $user =  $this->user_repo->create($request_data);

            $account_type = $request_data['account_type_id'];

            $types = [];
            $types = $this->generateTypes($types, $account_type, 1);
            if ($account_type != UsersTypesHelper::RESEARCHER_TYPE()){
                $types = $this->generateTypes($types, UsersTypesHelper::RESEARCHER_TYPE(), 0);
            }

            $user->accountTypes()->detach();
            $user->accountTypes()->attach($types);

            $user->load($this->user_repo->relationships());

            $user = AccountResource::make($user);
            return ApiResponse::format(201, $user, 'Account Created!');
        });

    }

    public function show($id)
    {
        $user = $this->user_repo->get($id);

        $user->load($this->user_repo->relationships());


        $user = AccountResource::make($user);
        return ApiResponse::format(200, $user);
    }

    public function update($id, $request = null)
    {
        $request_data = UsersHelper::prepareUpdateAccount($request->all());

        $user = $this->user_repo->update($id, $request_data);

        if (isset($request_data['account_type_id'])){
            $account_type = $request_data['account_type_id'];

            $types = [];
            $types = $this->generateTypes($types, $account_type, 1);
            if ($account_type != UsersTypesHelper::RESEARCHER_TYPE()){
                $types = $this->generateTypes($types, UsersTypesHelper::RESEARCHER_TYPE(), 0);
            }

            $user->accountTypes()->detach();
            $user->accountTypes()->attach($types);
        }

        $user->load($this->user_repo->relationships());

        return ApiResponse::format(200, $user);
    }

    public function delete($id)
    {
        $user = $this->user_repo->delete($id);
        return ApiResponse::format(200, $user, 'Account Deleted!');
    }


    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function generateTypes($types, $account_type, $main_type = 0){
        $types [] = [
            'user_type_id' => $account_type,
            'main_type' => $main_type,
        ];
        return $types;
    }

}
