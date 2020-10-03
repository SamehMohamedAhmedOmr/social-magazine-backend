<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AdminCountries extends Pivot
{
    protected $table = 'admin_countries';
    protected $fillable = [
        'admin_id' , 'country_id'
    ];
}
