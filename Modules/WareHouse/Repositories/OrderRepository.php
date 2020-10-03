<?php

namespace Modules\WareHouse\Repositories;

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

        $query = $query->with('orderItems.toppings', 'orderItems.product');

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function updateBulk($orders = [], $attributes = [])
    {
        $data = $orders != []
            ? $this->model->whereIn('id', $orders)
            : $this->model;

        $data = $data->update($attributes);

        return $data;
    }
}
