<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WarehouseDistrict extends Pivot
{
    protected $table = 'warehouse_district';
    protected $fillable = ['warehouse_id' , 'district_id'];
}
