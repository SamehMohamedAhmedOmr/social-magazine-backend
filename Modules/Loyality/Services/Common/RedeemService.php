<?php

namespace Modules\Loyality\Services\Common;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Loyality\Helpers\Facades\ValidationHelper;
use Modules\Loyality\Transformers\Repo\PointsLogResource as PointsLogResourceRepo;

class RedeemService extends LoyalityService
{

    /**
     * @param $request
     * @return array
     * @throws ValidationException
     * @throws \Throwable
     */
    public function validation($request)
    {
        $validator = Validator::make([], []);

        $response = ValidationHelper::validateProgram($this->loyality_program_data, $request->level_id,
            $request->points_needed);

        if (!$response['status']) {
            $validator->errors()->add('program', $response['errors']['program'][0]);
            throw new ValidationException($validator);
        }

        $points_needed = isset($request->points_needed) ? $request->points_needed : 0;

        if (isset($request->level_id)) {
            $price_to_points = $request->final_total_price * $this->loyality_program_data->price_to_points;

            $points_needed_row = $this->loyality_program_data
                ->levels()
                ->where('points', '<=', $price_to_points)
                ->find($request->level_id ?? 0);

            if ($points_needed_row === null) {
                $validator->errors()->add('level', 'invalid level');
                throw new ValidationException($validator);
            }

            $points_needed = $points_needed_row->points ?? 0;
        }

        // Validate With User's Points
        $response = ValidationHelper::validateUserPoints($request->user_id, $points_needed);

        if (!$response['status']) {
            $validator->errors()->add('points', $response['errors']['points'][0]);
        }

        // Validate With Program's settings
        $response = ValidationHelper::validatePointsWithProgram(
            $points_needed,
            $this->loyality_program_data,
            $request->products_price ?? null
        );

        if (!$response['status']) {
            $validator->errors()->add('points', $response['errors']['points'][0]);
        }

        throw_if($validator->errors()->count(), new ValidationException($validator));

        return [
            'points_needed' => $points_needed,
            'points_price' => $points_needed / $this->loyality_program_data->price_to_points
        ];
    }

    /**
     * @param $request
     * @return array
     * @throws ValidationException
     * @throws \Throwable
     */
    public function redeem($request)
    {
        $expiration_date = $this->loyality_program_data
            ? now()->addDays($this->loyality_program_data->days_until_expiration)->format('Y-m-d H:i:s')
            : now()->format('Y-m-d H:i:s');
        $refund_date = $this->loyality_program_data
            ? now()->addDays($this->loyality_program_data->days_until_refund)->format('Y-m-d H:i:s')
            : now()->format('Y-m-d H:i:s');

        $data = $this->calculation($request->points_needed);

        $point_log = PointsLogResourceRepo::toArray(
            $request->order_id,
            $request->user_id,
            $expiration_date,
            $refund_date,
            0,
            0,
            $data['points'],
            $data['money_saved']
        );

        $this->points_logs_repo->create($point_log, true);

        $this->user_point_repository->add($request->user_id, -($request->points_needed));

        return $data;
    }

    /**\
     * @param $points_needed
     * @return array
     */
    public function calculation($points_needed)
    {
        $data = [];
        $data['money_saved'] = $points_needed / $this->loyality_program_data->price_to_points;
        $data['points'] = $points_needed;

        return $data;
    }
}
