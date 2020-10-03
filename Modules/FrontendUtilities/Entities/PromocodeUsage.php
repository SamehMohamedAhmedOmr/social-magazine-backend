<?php

namespace Modules\FrontendUtilities\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\User;

/**
 * TODO uncomment whe orders & sales_orders are implemented
 */
// use Modules\Order\Entities\Order;
// use Modules\SalesOrder\Entities\SalesOrder;

class PromocodeUsage extends Model
{
    protected $table = 'promocode_usage';
    protected $fillable = [
        'discount',
        'order_id',
        'sales_order_id',
        'promocode_id',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }

    public function promocode()
    {
        return $this->belongsTo(Promocode::class, 'promocode_id', 'id');
    }

    /**
     * TODO uncomment whe orders & sales_orders are implemented
     */
    // public function order(){
    //     return $this->hasOne(Order::class,'user_id','id');
    // }

    // public function salesOrder(){
    //     return $this->hasOne(SalesOrder::class,'user_id','id');
    // }
}
