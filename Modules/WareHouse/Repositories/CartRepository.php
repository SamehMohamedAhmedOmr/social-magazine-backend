<?php

namespace Modules\WareHouse\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Cart;

class CartRepository extends LaravelRepositoryClass
{
    public function __construct(Cart $cart_model)
    {
        $this->model = $cart_model;
        $this->cache_key = 'cart';
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        return $this->model->with([
            'cartItems.product.brand.currentLanguage',
            'cartItems.product.currentLanguage',
            'cartItems.product.mainCategory.currentLanguage',
            'cartItems.product.favorites' => function ($query) {
                $query->where('user_id', Auth::id());
            }
            ,
            'cartItems.product.price',
            'cartItems.product.buyingPrice',
            'cartItems.toppings.currentLanguage',
            'cartItems.toppings.price',
            'cartItems.toppings.buyingPrice'
        ])->where($column, $value)->first();
    }

    public function updateOrCreate($optional_array, $required_array)
    {
        return $this->model->updateOrCreate($optional_array, $required_array);
    }

    /**
     * Count the given cart items based on the given
     * products, brands and categories.
     *
     * @param   Cart  $cart
     * @param   array $productsIds
     * @param   array $brandsIds
     * @param   array $categoriesIds
     *
     * @return  integer
     */
    public function countCartItems($cart, $productsIds = [], $brandsIds = [], $categoriesIds = [])
    {
        $cart = ! is_int($cart) ? $cart : $this->get($cart);
        return $cart ? $cart->cartItems()->whereHas('product', function ($query) use ($productsIds, $brandsIds, $categoriesIds) {
            if (count($productsIds)) {
                $query->whereIn('id', $productsIds);
            }
            if (count($brandsIds)) {
                $query->whereIn('brand_id', $brandsIds);
            }
            if (count($categoriesIds)) {
                $query
                ->whereIn('main_category_id', $categoriesIds)
                ->orWhereHas('categories', function ($query) use ($categoriesIds) {
                    $query->whereIn('id', $categoriesIds);
                });
            }
        })->count() : 0;
    }


    public function getPendingCart($carts)
    {
        return $this->model->whereIn('id', $carts)->count();
    }
}
