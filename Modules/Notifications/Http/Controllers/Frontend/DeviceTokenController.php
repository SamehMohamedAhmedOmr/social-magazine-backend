<?php

namespace Modules\Notifications\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;
use Modules\Notifications\Http\Requests\DeviceTokenRequest;
use Modules\Notifications\Services\Frontend\DeviceTokenService;

class DeviceTokenController extends Controller
{
    private $device_token_service;

    public function __construct(DeviceTokenService $device_token_service)
    {
        $this->device_token_service = $device_token_service;
    }

    /**
     * Store a newly created resource in storage.
     * @param DeviceTokenRequest $request
     * @return void
     */
    public function store(DeviceTokenRequest $request)
    {
        return $this->device_token_service->store();
    }
}
