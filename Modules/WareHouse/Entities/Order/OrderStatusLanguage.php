<?php

namespace Modules\WareHouse\Entities\Order;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Language;

class OrderStatusLanguage extends Model
{
    protected $table = 'order_status_language';
    protected $fillable = ['language_id', 'order_status_id', 'name'];

    // Relation For CMS
    public function languages()
    {
        return $this->hasMany(OrderStatusLanguage::class, 'order_status_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

}
