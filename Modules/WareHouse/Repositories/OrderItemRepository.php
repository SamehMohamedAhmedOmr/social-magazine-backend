<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Order\Order;
use Modules\WareHouse\Entities\Order\OrderItem;

class OrderItemRepository extends LaravelRepositoryClass
{
    public function __construct(OrderItem $orderItem)
    {
        $this->model = $orderItem;
        $this->cache_key = 'order_item';
    }

    public function attachTopping($model, $product)
    {
        $model->toppings()->attach($product);
    }

    public function detachTopping($model, $product)
    {
        $model->toppings()->detach($product);
    }
}
