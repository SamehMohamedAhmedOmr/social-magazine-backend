<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItemsTopping extends Pivot
{
    protected $table = 'cart_item_toppings';
    protected $fillable = ['cart_item_id','topping_id'];
}
