<?php

namespace Modules\WareHouse\Entities\Order;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItemsTopping extends Pivot
{
    protected $table = 'order_items_toppings';

    protected $fillable = [
        'order_item_id',
        'topping_id',
        'price' ,
        'quantity',
        'buying_price'
    ];
}
