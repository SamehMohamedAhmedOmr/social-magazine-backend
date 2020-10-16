<?php

namespace Modules\Users\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Users\Entities\User;

class UserRepository extends LaravelRepositoryClass
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = null,
                             $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->filtering($search_keys);

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function all($conditions = [], $search_keys = null)
    {
        $query = $this->filtering($search_keys);

        return $query->where($conditions)->get();
    }

    private function filtering($search_keys){
        $query = $this->model;

        $account_type_id = request('account_type_id');


        if ($account_type_id){
            $query = $query->with(['accountTypes']);

            $query = $query->whereHas('accountTypes', function ($account_type_query) use ($account_type_id) {
                $account_type_query->where('id', $account_type_id)->where('main_type', 1);
            });
        }

        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){
                $q->where('first_name', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('family_name', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('email', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('id', 'LIKE', '%'.$search_keys.'%');
            });
        }

        return $query;
    }


    public function getData($conditions = [])
    {
        return $this->model->where($conditions)->first();
    }

    public function syncRoles($model, $roles)
    {
        $model->roles()->detach();
        $model->roles()->attach($roles);
    }

    public function relationships($extra_relationships = []){
        return array_merge([
            'gender',
            'title',
            'educationalLevel',
            'educationalDegree',
            'country',
            'accountTypes'
        ],$extra_relationships);
    }

    public function AuthAttempt()
    {
        return Auth::attempt(
            [
                'email' => request('email'),
                'password' => request('password')
            ]
        );
    }
}
