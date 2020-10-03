<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WarehouseLanguage extends Pivot
{
    protected $table = 'warehouse_languages';
    protected $fillable = ['name','description','language_id'];
}
