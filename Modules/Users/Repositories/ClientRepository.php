<?php

namespace Modules\Users\Repositories;

use Carbon\Carbon;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Users\Entities\Researcher;
use Modules\Users\Entities\User;

class ClientRepository extends LaravelRepositoryClass
{
    protected $user;
    protected $client_type = 2;
    public function __construct(Researcher $client, User $user)
    {
        $this->model = $client;
        $this->user = $user;
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = null, $sort_key = 'id', $sort_order = 'asc', $lang = null)
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
        $query = $this->user;

        $query = $query->with('client');

        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){

                $q = $q->whereHas('client', function ($client_query) use ($search_keys) {
                    if ($search_keys) {
                        $client_query->where('phone', 'LIKE', '%'.$search_keys.'%');
                    }
                });

                $q->orWhere('name', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('email', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('id', 'LIKE', '%'.$search_keys.'%');
            });
        }


        if (request('subscribed_product')){

            $query = $query->with('productSubscribed');

            $query = $query->whereHas('productSubscribed', function ($product_query) {
                $product_query->where('id', request('subscribed_product'));
            });
        }
        return $query;
    }

    public function getData($conditions = [])
    {
        return $this->model->where($conditions)->first();
    }

    public function getClientLastMonth()
    {
        return $this->model->whereDate('created_at', '>=', Carbon::now()->subMonth())->count();
    }
}
