<?php

namespace Modules\Loyality\Transformers\Repo;

class PointsLogResource
{
    public static function toArray(
        $order_id,
        $user_id,
        $expiration_date,
        $refund_date,
        $points = 0,
        $money_spent = 0,
        $points_redeemed = 0,
        $money_saved = 0
    )
    {
        return [
            'order_id' => $order_id,
            'user_id' => $user_id,
            'money_spent' => $money_spent ?? 0,
            'money_saved' => $money_saved ?? 0,
            'points_gained' => $points ?? 0,
            'points_redeemed' => $points_redeemed ?? 0,
            'expiration_date' => $expiration_date,
            'status' => 'pending',
            'refund_date' => $refund_date
        ];
    }
}
