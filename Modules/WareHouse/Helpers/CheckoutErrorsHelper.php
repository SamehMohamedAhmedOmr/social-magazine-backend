<?php

namespace Modules\WareHouse\Helpers;

use Illuminate\Validation\ValidationException;

/**
 * Class CheckoutErrorsHelper
 * @package Modules\WareHouse\Helpers
 */
class CheckoutErrorsHelper
{

    /**
     * @throws ValidationException
     */
    public function noSellingPriceList()
    {
        throw ValidationException::withMessages([
            'price' => trans('warehouse::errors.noSellingPriceList'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function noSellingPriceListForTopping()
    {
        throw ValidationException::withMessages([
            'price' => trans('warehouse::errors.noSellingPriceListForTopping'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function noDistrict()
    {
        throw ValidationException::withMessages([
            'district' => trans('warehouse::errors.noDistrict'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function noDistrictAvailable()
    {
        throw ValidationException::withMessages([
            'district' => trans('warehouse::errors.noDistrictAvailable'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function noCartItem()
    {
        throw ValidationException::withMessages([
            'cart' => trans('warehouse::errors.noCartItem'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function notActiveItem()
    {
        throw ValidationException::withMessages([
            'cart' => trans('warehouse::errors.notActiveItem'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function notActiveItemTopping()
    {
        throw ValidationException::withMessages([
            'topping' => trans('warehouse::errors.notActiveItemTopping'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function itemOutOfStock()
    {
        throw ValidationException::withMessages([
            'cart' => trans('warehouse::errors.itemOutOfStock'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function itemToppingOutOfStock()
    {
        throw ValidationException::withMessages([
            'topping' => trans('warehouse::errors.itemToppingOutOfStock'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function itemsDuplicated()
    {
        throw ValidationException::withMessages([
            'items' => trans('warehouse::errors.itemsDuplicated'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function toppingDuplicate()
    {
        throw ValidationException::withMessages([
            'toppings' => trans('warehouse::errors.toppingDuplicate'),
        ]);
    }
}
