<?php

namespace Modules\FrontendUtilities\Helpers;

use Illuminate\Validation\ValidationException;

class PromocodeErrorsHelper
{
    public function promoCodeInvalid()
    {
        throw ValidationException::withMessages([
            'promocode' => trans('frontendutilities::errors.promoCodeInvalid'),
        ]);
    }

    public function promoCodeExpired()
    {
        throw ValidationException::withMessages([
            'promocode' => trans('frontendutilities::errors.promoCodeExpired'),
        ]);
    }

    public function promoCodeUsed()
    {
        throw ValidationException::withMessages([
            'promocode' => trans('frontendutilities::errors.promoCodeUsed'),
        ]);
    }

    public function promoCodeMinPrice($min_price)
    {
        throw ValidationException::withMessages([
            'promocode' => trans('frontendutilities::errors.promoCodeMinPrice', ['price' => $min_price]),
        ]);
    }

    public function promoCodeMaxPrice($max_price)
    {
        throw ValidationException::withMessages([
            'promocode' => trans('frontendutilities::errors.promoCodeMaxPrice', ['price' => $max_price]),
        ]);
    }

    public function promoCodeIvalidForProducts()
    {
        throw ValidationException::withMessages([
            'promocode' => trans('frontendutilities::errors.promoCodeIvalidForProducts'),
        ]);
    }
}
