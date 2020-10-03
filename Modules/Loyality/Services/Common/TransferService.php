<?php

namespace Modules\Loyality\Services\Common;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Loyality\Helpers\Facades\ValidationHelper;

class TransferService extends LoyalityService
{
    public function transfer($request)
    {
        $this->validate($request->from_user_id, $request->points_needed);

        // Transfer
        $success_failed = $this->points_logs_repo->transfer(
            $request->from_user_id,
            $request->to_user_id,
            $request->points_needed
        );

        if ($success_failed) {
            $data['points'] = $this->user_point_repository->transfer(
                $request->from_user_id,
                $request->to_user_id,
                $request->points_needed
            );

            return ApiResponse::format(200, $data, 'Transferred Successfully');
        }

        $validator = Validator::make([], []);
        $validator->errors()->add('point', 'Failed transaction because of refund date');
        throw new ValidationException($validator);
    }

    private function validate($from_user_id, $points_needed)
    {
        $validator = Validator::make([], []);
        $response = ValidationHelper::validateUserPoints($from_user_id, $points_needed);
        if (!$response['status']) {
            $validator->errors()->add('points', $response['errors']['points'][0]);
        }

        throw_if($validator->errors()->count(), new ValidationException($validator));
    }
}
