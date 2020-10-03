<?php

namespace Modules\WareHouse\Repositories\CMS\Order;

use Carbon\Carbon;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Order\Order;

class OrderRepository extends LaravelRepositoryClass
{
    public function __construct(Order $order_model)
    {
        $this->model = $order_model;
        $this->cache_key = 'order';
    }

    public function paginate(
        $per_page = 15,
        $conditions = null,
        $search_keys = null,
        $sort_key = 'id',
        $sort_order = 'asc',
        $lang = null,
        $whereIn = []
    )
    {
        $query = $this->model;

        if (count($whereIn)) {
            $query = $query->whereIn('warehouse_id', $whereIn);
        }

        $query = $query->with(['orderItems.toppings', 'orderItems.product']);

        $query = $this->prepareFilteringConditions($query);

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function getBulk($orders = [], $whereIn = [])
    {
        $query = $this->model;
        $query = $orders != [] ? $query->whereIn('id',$orders) : $query;

        if (count($whereIn)) {
            $query = $query->whereIn('warehouse_id', $whereIn);
        }

        $query = $query->with(['orderItems.toppings', 'orderItems.product']);

        $query = $this->prepareFilteringConditions($query);

        return $query->get();
    }

    private function prepareFilteringConditions($query){

        if (request()->has('user_id')) {
            $query = $query->where('user_id', request('user_id'));
        }

        elseif (request()->has('product_id')){
            $query = $query->whereHas('orderItems', function ($product_query) {
                $product_query->where('product_id', request('product_id'));
            });
        }

        elseif (request()->has('order_id')){
            $query = $query->where('id', request('order_id'));
        }

        elseif (request()->has('district_id')){
            $query = $query->with(['address']);
            $query = $query->whereHas('address', function ($district_query) {
                $district_query->where('district_id', request('district_id'));
            });
        }

        elseif (request()->has('payment_method_id')){
            $query = $query->where('payment_method_id', request('payment_method_id'));
        }

        elseif (request()->has('status')){
            $query = $query->where('order_status_id', request('status'));
        }

        elseif (request()->has('from')){
            $query = $query->whereDate('created_at', '>=' , Carbon::make(request('from')));
            if (request('to')){
                $query = $query->whereDate('created_at', '<=' , Carbon::make( request('to')));
            }
        }

        elseif (request()->has('to')){
            $query = $query->whereDate('created_at', '<=' , request('to'));
        }

        return $query;
    }
}
