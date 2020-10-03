<?php

namespace Modules\FrontendUtilities\Helpers;

use Modules\WareHouse\Repositories\CartRepository;
use Modules\FrontendUtilities\Repositories\PromocodesRepository;

class PromocodeValidationHelper
{
    /**
     * @var object
     */
    private $cart_repo;
    
    /**
     * @var object
     */
    private $promocodes_repository;

    public function __construct(PromocodesRepository $promocodes_repository, CartRepository $cart_repo)
    {
        $this->promocodes_repository = $promocodes_repository;
        $this->cart_repo = $cart_repo;
    }
    
    /**
     * Load promocode form the given code string.
     *
     * @param   mixed  $code
     *
     * @return  Modules\FrontendUtilities\Entities\Promocode
     */
    public function get($code)
    {
        return is_string($code) ? $this->promocodes_repository->get($code, [], 'code') : $code;
    }

    /**
     * Check if the given code is expired.
     *
     * @param   mixed  $code
     *
     * @return  boolean
     */
    public function isExpired($code)
    {
        $promocode = $this->get($code);
        return $promocode->from > date('Y-m-d H:i:s') || $promocode->to < date('Y-m-d H:i:s');
    }

    /**
     * Check if the given code is used for the given user.
     *
     * @param   mixed   $code
     * @param   integer $user_id
     *
     * @return  boolean
     */
    public function isUsed($code, $user_id)
    {
        $promocode = $this->get($code);
        $userUsageCount = $this->promocodes_repository->usagePerUserCount($promocode, $user_id);
        return $promocode->total_usage_count >= $promocode->users_count || $userUsageCount >= $promocode->usage_per_user;
    }

    /**
     * Check if the given code is valid for the given user.
     *
     * @param   mixed   $code
     * @param   integer $user_id
     *
     * @return  boolean
     */
    public function validToUser($code, $user_id)
    {
        $promocode = $this->get($code);
        if ($this->promocodes_repository->userCount($promocode)) {
            return $this->promocodes_repository->userCount($promocode, $user_id);
        }

        return true;
    }

    /**
     * Check if the given code is below the minimum price.
     *
     * @param   mixed   $code
     * @param   float   $price
     *
     * @return  boolean
     */
    public function isBelowMinPrice($code, $price)
    {
        $promocode = $this->get($code);
        return $price < $promocode->minimum_price;
    }

    /**
     * Check if the given code is over the maximum price.
     *
     * @param   mixed   $code
     * @param   float   $price
     *
     * @return  boolean
     */
    public function isOverMaxPrice($code, $price)
    {
        $promocode = $this->get($code);
        return $price > $promocode->maximum_price;
    }

    /**
     * Check if the given code is over the maximum price.
     *
     * @param   mixed                            $code
     * @param   Modules\WareHouse\Entities\Cart  $cart
     *
     * @return  boolean
     */
    public function isValidForCartItems($code, $cart)
    {
        $promocode = $this->get($code);
        $promocodeProductsIds = $promocode->products->pluck('id');
        $promocodeBrandsIds = $promocode->brands->pluck('id');
        $promocodeCategoriesIds = $promocode->categories->pluck('id');

        return $this->cart_repo->countCartItems($cart, $promocodeProductsIds, $promocodeBrandsIds, $promocodeCategoriesIds);
    }
}
