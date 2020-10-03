<?php

namespace Modules\WareHouse\Helpers;

use Illuminate\Validation\ValidationException;

/**
 * Class CheckoutErrorsHelper
 * @package Modules\WareHouse\Helpers
 */
class CountryErrorsHelper
{
    /**
     * @throws ValidationException
     */
    public function cannotDeleteDefaultCountry()
    {
        throw ValidationException::withMessages([
            'country_code' => trans('warehouse::errors.cannotDeleteDefaultCountry'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function cannotUpdateDefaultCountry()
    {
        throw ValidationException::withMessages([
            'country_code' => trans('warehouse::errors.cannotUpdateDefaultCountry'),
        ]);
    }
}
