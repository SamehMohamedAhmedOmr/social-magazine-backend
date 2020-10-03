<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AdminWarehouses extends Pivot
{
    protected $table = 'admin_warehouses';
    protected $fillable = [
        'admin_id' , 'warehouse_id'
    ];
}
