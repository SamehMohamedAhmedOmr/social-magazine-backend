<?php

namespace Modules\WareHouse\Entities\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FrontendUtilities\Entities\PromocodeUsage;
use Modules\Base\Scopes\CountryScope;
use Modules\Loyality\Entities\PointLog;
use Modules\Settings\Entities\PaymentMethod;
use Modules\Settings\Entities\TimeSection;
use Modules\Users\Entities\Address;
use Modules\Users\Entities\User;
use Modules\WareHouse\Entities\Shipment\Shipment;
use Modules\WareHouse\Entities\Warehouse;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'order_status_id', 'is_autoship', 'is_pickup', 'is_restored', 'is_active',
        'delivery_date',
        'shipping_price', 'total_price', 'discount', 'vat',
        'cancellation_reason',
        'device_id', 'device_os', 'app_version',
        'user_id', 'payment_method_id', 'address_id',
        'time_section_id', 'shipping_rule_id', 'warehouse_id',
        'external_sales_order_id', 'payment_order_id', 'country_id',
        'loyality_discount'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CountryScope);
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }


    public function orderNotes()
    {
        return $this->hasMany(OrderNotes::class, 'order_id', 'id');
    }

    public function timeSection()
    {
        return $this->belongsTo(TimeSection::class, 'time_section_id', 'id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function promoCodeUsage()
    {
        return $this->hasOne(PromocodeUsage::class, 'order_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class, 'order_id');
    }

    public function loyality()
    {
        return $this->hasOne(PointLog::class, 'order_id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }
}
