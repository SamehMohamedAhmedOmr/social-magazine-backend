<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CountryLanguage extends Pivot
{
    protected $table = 'country_languages';
    protected $fillable = [
        'country_id',
        'language_id',
        'name',
        'description'
    ];
}
