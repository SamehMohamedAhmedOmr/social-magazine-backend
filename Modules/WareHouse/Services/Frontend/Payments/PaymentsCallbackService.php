<?php

namespace Modules\WareHouse\Services\Frontend\Payments;

use Illuminate\Http\Request;
use Modules\Base\ResponseShape\ApiResponse;

class PaymentsCallbackService
{
    protected $weAccept;

    public function __construct(WeAccept $weAccept)
    {
        $this->weAccept = $weAccept;
    }

    public function weAcceptProcessed(Request $request)
    {
        $this->weAccept->createLog($request->success, $request->order, $request->all());
        $validation = $this->weAccept->validateCallbackHmac($request);
        if (!$validation) {
            return ApiResponse::format(400, [
                    'success' => false, 'data.message' => 'failed', 'acq_response_code' => 44,
                    'order_id' => $request->order_id ?? null
                ], 'Failed');
        }
        if (!$request->success) {
            return ApiResponse::format(400, [], 'Sorry we were unable to complete the checkout process .. Please try again later');
        }

        $this->weAccept->updateOrder($request->order, $request->success);
        return ApiResponse::format(200, [], 'Successful');
    }

    public function weAcceptResponse(Request $request)
    {
        $validation = $this->weAccept->validateCallbackHmac($request);
        if (!$validation) {
            return ApiResponse::format(400, [
                    'success' => false, 'data.message' => 'failed', 'acq_response_code' => 44,
                    'order_id' => $request->order_id ?? null
                ], 'Failed');
        }

        if (!$request->success) {
            return ApiResponse::format(400, [], 'Thank you for using the online payment service.You have successfully paid EGP ' . $request->amount_cents/ 100 . ' to PAM');
        } else {
            return ApiResponse::format(200, [], 'Sorry we were unable to complete the checkout process .. Please try again later');
        }
    }
}
