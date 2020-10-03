<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DaysLanguages extends Pivot
{
    protected $fillable = [
        'day_id' ,
        'language_id' ,
        'name'
    ];
}
