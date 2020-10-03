<?php

namespace Modules\Users\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Users\Entities\CMSUser;

class CMSUsersRepository extends LaravelRepositoryClass
{
    public function __construct(CMSUser $admin)
    {
        $this->model = $admin;
    }

}
