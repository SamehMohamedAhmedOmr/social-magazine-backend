<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TimeSectionDays extends Pivot
{
    protected $fillable = [
        'day_id' ,
        'time_section_id' ,
    ];
}
