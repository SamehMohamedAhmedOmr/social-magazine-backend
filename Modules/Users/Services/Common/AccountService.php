<?php

namespace Modules\Users\Services\Common;

use Illuminate\Http\JsonResponse;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Users\Facades\UsersTypesHelper;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Transformers\CMS\AccountResource;
use Throwable;

class AccountService extends LaravelServiceClass
{
    private $user_repo;

    public function __construct(UserRepository $user_repo)
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
            $user_data = $request->all();
            $user_data['user_type'] =  UsersTypesHelper::RESEARCHER_TYPE();

            if (isset($user_data['password'])){
                $user_data['password'] = bcrypt($user_data['password']);
            }
            else{
                $user_data['password'] = bcrypt($this->randomPassword());
            }

            $user =  $this->user_repo->create($user_data);

            $this->clientRepository->create([
                'user_id' => $user->id,
                'phone' => $request->phone
            ]);


            $user = ClientResource::make($user);
            return ApiResponse::format(201, $user, 'Researcher Added!');
        });

    }

    public function show($id)
    {
        $user = $this->user_repo->get($id, [
            'user_type' =>  UsersTypesHelper::RESEARCHER_TYPE()
        ]);

        if (request('get_address')) {
            $user->load('address');
        }

        if (request('get_orders')) {
            $user->load('orders');
        }

        $user = ClientResource::make($user);
        return ApiResponse::format(200, $user);
    }

    public function update($id, $request = null)
    {
        $user = $this->user_repo->update($id, $request->only('name', 'email', 'is_active'));

        if ($request->phone) {
            $this->clientRepository->update($user->id, [
                'phone' => $request->phone
            ], [], 'user_id');
        }

        $user = ClientResource::make($user);
        return ApiResponse::format(200, $user);
    }

    public function delete($id)
    {
        $user = $this->user_repo->delete($id);
        return ApiResponse::format(200, $user, 'Researcher Deleted!');
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

}
