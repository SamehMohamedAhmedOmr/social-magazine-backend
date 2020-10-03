<?php

namespace Modules\Loyality\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\WareHouse\Entities\Order\Order;

class PointLog extends Model
{
    protected $table = 'points_logs';
    protected $fillable = [
        'money_spent', 'money_saved',
        'points_gained', 'points_redeemed',
        'user_id', 'order_id',
        'expiration_date', 'refund_date',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
