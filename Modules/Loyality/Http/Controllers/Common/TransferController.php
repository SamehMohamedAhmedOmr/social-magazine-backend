<?php

namespace Modules\Loyality\Http\Controllers\Common;

use Illuminate\Routing\Controller;
use Modules\Loyality\Http\Requests\Common\TransferPointsRequest;
use Modules\Loyality\Services\Common\TransferService;

class TransferController extends Controller
{
    private $transfer_service;

    public function __construct(TransferService $transfer_service)
    {
        $this->transfer_service = $transfer_service;
    }

    public function transfer(TransferPointsRequest $request)
    {
        return $this->transfer_service->transfer($request);
    }
}
