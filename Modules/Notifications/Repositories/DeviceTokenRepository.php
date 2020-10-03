<?php


namespace Modules\Notifications\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Notifications\Entities\DeviceToken;

class DeviceTokenRepository extends LaravelRepositoryClass
{
    public function __construct(DeviceToken $device_token)
    {
        $this->model = $device_token;
        $this->cache = 'device_token';
    }

    public function updateOrCreate($optional_array, $required_array)
    {
        return $this->model->updateOrCreate($optional_array, $required_array);
    }

    public function whereIn($column, array $values)
    {
        return $this->model->whereIn($column, $values)->get();
    }
}
