<?php

namespace Modules\WareHouse\Entities\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderNotes extends Model
{
    use SoftDeletes;
    protected $table = 'order_notes';
    protected $fillable = [
        'order_id' , 'note'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
