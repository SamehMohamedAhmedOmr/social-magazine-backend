<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DistrictLanguage extends Pivot
{
    protected $table = 'district_languages';

    protected $fillable = [
        'district_id',
        'language_id',
        'name',
        'description'
    ];
}
