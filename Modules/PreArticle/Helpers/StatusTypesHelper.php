<?php

namespace Modules\PreArticle\Helpers;


/**
 * Class UsersErrorsHelper
 * @package Modules\Settings\Helpers
 */
class StatusTypesHelper
{

    public function new()
    {
        return 'NEW';
    }

    public function specialized()
    {
        return 'SPECIALIZED';
    }

    public function review()
    {
        return 'REVIEW';
    }

    public function rejected()
    {
        return 'REJECTED';
    }

    public function accepted()
    {
        return 'ACCEPTED';
    }

    public function withdrawal()
    {
        return 'WITHDRAWAL';
    }


}
