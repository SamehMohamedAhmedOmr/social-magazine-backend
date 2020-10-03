<?php

namespace Modules\WareHouse\Entities\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    use SoftDeletes;

    protected $table = 'order_statuses';
    protected $fillable = ['is_active', 'key'];

    // Relation For CMS
    public function languages()
    {
        return $this->hasMany(OrderStatusLanguage::class, 'order_status_id');
    }

    // Relation For Front
    public function currentLanguage()
    {
        return $this->hasOne(OrderStatusLanguage::class, 'order_status_id')
            ->where('language_id', \Session::get('language_id'));
    }
}
