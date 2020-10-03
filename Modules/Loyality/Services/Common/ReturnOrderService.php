<?php

namespace Modules\Loyality\Services\Common;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Base\ResponseShape\ApiResponse;

class ReturnOrderService extends LoyalityService
{
    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function returnOrder($request)
    {
        $response = $this->points_logs_repo->returnOrder($request->order_id);
        $validator = Validator::make([], []);

        if (!is_array($response)) {
            $validator->errors()->add('order', $response);
            throw new ValidationException($validator);
        }

        return ApiResponse::format(200, $response, 'Returned Successfully');
    }
}
