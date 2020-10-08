<?php

namespace Modules\Users\Helpers;

use Illuminate\Validation\ValidationException;

/**
 * Class UsersTypesHelper
 * @package Modules\Users\Helpers
 */
class UsersTypesHelper
{

    public function MAGAZINE_EDITOR_MANAGER_TYPE(){
        return 1;
    }

    public function JOURNAL_EDITOR_DIRECTOR_TYPE(){
        return 2;
    }

    public function REFEREES_TYPE(){
        return 3;
    }

    public function RESEARCHER_TYPE(){
        return 4;
    }


}
