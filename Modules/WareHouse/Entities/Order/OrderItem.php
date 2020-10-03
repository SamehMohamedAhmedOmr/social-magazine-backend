<?php

namespace Modules\WareHouse\Entities\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Catalogue\Entities\Product;
use Modules\Catalogue\Entities\ToppingMenu;

class OrderItem extends Model
{
    use SoftDeletes;
    protected $table = 'order_items';
    protected $fillable = ['order_id','product_id','quantity','has_toppings','price', 'buying_price'];

    public function toppings()
    {
        return $this->belongsToMany(
            Product::class,
            'order_items_toppings',
            'order_item_id',
            'topping_id'
        )
            ->using(OrderItemsTopping::class)
            ->withPivot('price', 'quantity');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
