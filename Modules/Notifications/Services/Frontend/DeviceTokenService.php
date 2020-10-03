<?php

namespace Modules\Notifications\Services\Frontend;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Notifications\Repositories\DeviceTokenRepository;
use Modules\Notifications\Transformers\DeviceTokenResource;

class DeviceTokenService extends LaravelServiceClass
{
    private $device_token_repository;

    public function __construct(DeviceTokenRepository $device_token_repository)
    {
        $this->device_token_repository = $device_token_repository;
    }

    public function store()
    {
        $mac_address = strtok(exec('getmac'), ' ');

        list($optional_array, $required_array) = DeviceTokenResource::prepareStoreMethod($mac_address);

        $device_data = $this->device_token_repository->updateOrCreate($optional_array, $required_array);

        $device_data = DeviceTokenResource::make($device_data);
        return ApiResponse::format(200, $device_data);
    }
}
