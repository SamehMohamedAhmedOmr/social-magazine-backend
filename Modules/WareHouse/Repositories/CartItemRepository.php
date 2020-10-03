<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Cart;
use Modules\WareHouse\Entities\CartItem;

class CartItemRepository extends LaravelRepositoryClass
{
    public function __construct(CartItem $cart_item_model)
    {
        $this->model = $cart_item_model;
        $this->cache_key = 'cart_items';
    }

    public function attachTopping($model, $topping)
    {
        $model->toppings()->attach($topping);
    }

    public function detachTopping($model)
    {
        $model->toppings()->detach();
    }

    public function deleteBulk($items_id)
    {
        $this->model->whereIn('id', $items_id)->delete();
    }
}
