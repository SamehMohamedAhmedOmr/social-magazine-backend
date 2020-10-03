<?php

namespace Modules\WareHouse\Entities\Shipment;

use Illuminate\Database\Eloquent\Model;
use Modules\WareHouse\Entities\Order\Order;

class Shipment extends Model
{
    protected $table = 'shipments';
    protected $fillable = ['tacking_id', 'tracking_number', 'current_status', 'receipt', 'shipping_company_id', 'order_id'];

    public function shippingCompany()
    {
        return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
