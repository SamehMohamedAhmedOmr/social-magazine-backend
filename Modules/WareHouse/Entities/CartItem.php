<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Catalogue\Entities\Product;
use Modules\Catalogue\Entities\ToppingMenu;

class CartItem extends Model
{
    protected $table = 'cart_items';
    protected $fillable = ['cart_id','product_id','quantity','has_toppings'];

    public function toppings()
    {
        return $this->belongsToMany(
            Product::class,
            'cart_item_toppings',
            'cart_item_id',
            'topping_id'
        )
            ->using(CartItemsTopping::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
