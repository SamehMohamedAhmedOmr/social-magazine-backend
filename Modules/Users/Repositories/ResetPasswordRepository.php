<?php

namespace Modules\Users\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Users\Entities\Address;
use Modules\Users\Entities\PasswordReset;

class ResetPasswordRepository extends LaravelRepositoryClass
{
    public function __construct(PasswordReset $passwordReset)
    {
        $this->model = $passwordReset;
    }

    public function updateOrCreate($optional_array, $required_array)
    {
        return $this->model->updateOrCreate($optional_array, $required_array);
    }

    public function getData($conditions = [])
    {
        return $this->model->where($conditions)->first();
    }
}
