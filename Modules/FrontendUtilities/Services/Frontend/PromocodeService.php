<?php

namespace Modules\FrontendUtilities\Services\Frontend;

use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\FrontendUtilities\Entities\Promocode;
use Modules\FrontendUtilities\Repositories\PromocodesRepository;
use Modules\FrontendUtilities\Helpers\PromocodeValidationHelper;
use Modules\WareHouse\Services\Frontend\CartService;
use PromocodeErrorsHelper;
use UtilitiesHelper;
use Auth;

class PromocodeService extends LaravelServiceClass
{
    const DISCOUNT_PERCENTAGE_TYPE = 1;
    const DISCOUNT_TYPES = ['fixed', 'percentage'];

    private $cart_service;

    private $validator;

    private $promocodes_repository;

    public function __construct(PromocodesRepository $promocodes_repository, CartService $cart_service, PromocodeValidationHelper $validator)
    {
        $this->promocodes_repository = $promocodes_repository;
        $this->cart_service = $cart_service;
        $this->validator = $validator;
    }

    /**
     * Load promocode form the given code string.
     *
     * @param mixed $code
     *
     * @return Promocode
     */
    public function get($code)
    {
        return is_string($code) ? $this->promocodes_repository->get($code, [], 'code') : $code;
    }

    /**
     * Calculate discount for the given price.
     *
     * @param mixed $code
     * @param float $price
     *
     * @return  boolean
     */
    public function calculateDiscount($code, $price)
    {
        $promocode = $this->get($code);

        if ($promocode->discount_type === self::DISCOUNT_PERCENTAGE_TYPE) {
            $discount = ($promocode->reward * $price / 100);
            return $discount > $promocode->max_discount_amount ? $promocode->max_discount_amount : $discount;
        }

        return $promocode->reward;
    }

    /**
     * Validate the given promocode.
     *
     * @param string $code
     * @param mixed $price
     *
     * @param mixed $user
     * @return array
     */
    public function validate($code, $price = false, $user = false)
    {
        $user = $user ?: Auth::user()->load('cart');

        $promocode = $this->get($code);

        if (!$promocode->is_active || !$this->validator->validToUser($promocode, $user->id)) {
            PromocodeErrorsHelper::promoCodeInvalid();
        }

        if ($this->validator->isExpired($promocode)) {
            PromocodeErrorsHelper::promoCodeExpired();
        }

        if ($this->validator->isUsed($promocode, $user->id)) {
            PromocodeErrorsHelper::promoCodeUsed();
        }

        if (!$price) {
            list($price) = $this->cart_service->calculateCart($user->cart->cartItems);
        }

        if ($this->validator->isBelowMinPrice($promocode, $price)) {
            PromocodeErrorsHelper::promoCodeMinPrice($promocode->minimum_price);
        }

        if ($this->validator->isOverMaxPrice($promocode, $price)) {
            PromocodeErrorsHelper::promoCodeMaxPrice($promocode->maximum_price);
        }

        return [
            'amount_after_discount' => $this->calculateDiscount($promocode, $price),
            'discount_type' => self::DISCOUNT_TYPES[$promocode->discount_type],
            'discount_rate' => $promocode->reward,
            'has_free_shipping' => $promocode->is_free_shipping,
            'promocode_id' => $promocode->id,
        ];
    }
}
