<?php

namespace Modules\Users\Services\Common;

use http\Client;
use Illuminate\Support\Facades\Auth;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Users\Repositories\ResearcherRepository;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Transformers\AdminResource;
use Modules\Users\Transformers\ClientResource;
use Modules\Users\Transformers\UserResource;
use Modules\Users\Transformers\UserSummaryResource;

class UserService extends LaravelServiceClass
{
    private $user_repo;
    private $client_repo;
    protected $admin_type = 1;
    protected $client_type = 2;


    public function __construct(
        UserRepository $user,
        ResearcherRepository $client_repo
    )
    {
        $this->user_repo = $user;
        $this->client_repo = $client_repo;
    }

    public function userSummary()
    {
        if (request('is_pagination')) {
            list($users, $pagination) = parent::paginate($this->user_repo, null, false, [
                'user_type' => $this->client_type
            ]);
        } else {
            $users = $this->user_repo->all([
                'user_type' => $this->client_type
            ]);
            $pagination = null;
        }

        $users->load('client');

        if (request('get_address')) {
            $users->load('address');
        }

        if (request('get_orders')) {
            $users->load([
                'orders.paymentMethod',
                'orders.orderItems.toppings'
            ]);
        }

        $users = UserSummaryResource::collection($users);
        return ApiResponse::format(200, $users, [], $pagination);
    }

    public function get()
    {
        $user = Auth::user();

        $user = $this->getUserResource($user);

        return ApiResponse::format(201, $user);
    }

    public function update($id, $request = null)
    {
        $user = $this->user_repo->update($id, $request->all());

        if ($request->has('phone') && $user->user_type == $this->client_type) {
            $this->client_repo->update($user->client->id, [
                'phone' => $request->phone
            ]);
        }

        $user = $this->getUserResource($user);

        return ApiResponse::format(201, $user);
    }

    private function getUserResource($user)
    {
        if ($user->user_type == $this->admin_type) {
            $user->load('admin.countries.language');
            $user = AdminResource::make($user);
        } elseif ($user->user_type == $this->client_type) {
            $user->load('client');
            $user = ClientResource::make($user);
        }
        return $user;
    }
}
