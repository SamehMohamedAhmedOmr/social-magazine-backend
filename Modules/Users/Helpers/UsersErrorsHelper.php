<?php

namespace Modules\Users\Helpers;

use Illuminate\Validation\ValidationException;

/**
 * Class UsersErrorsHelper
 * @package Modules\Users\Helpers
 */
class UsersErrorsHelper
{

    /**
     * @throws ValidationException
     */
    public function unAuthenticated()
    {
        throw ValidationException::withMessages([
            'credential' => trans('users::errors.credential'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function inCorrectPassword()
    {
        throw ValidationException::withMessages([
            'credential' => trans('users::errors.inCorrectPassword'),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function tokenInvalid()
    {
        throw ValidationException::withMessages([
            'credential' => trans('users::errors.token_invalid'),
        ]);
    }


    /**
     * @throws ValidationException
     */
    public function tokenExpired()
    {
        throw ValidationException::withMessages([
            'credential' => trans('users::errors.token_expired'),
        ]);
    }

    public function regexName(){
        return '/^[a-z ,.\'_-]+$/i';
    }
}
