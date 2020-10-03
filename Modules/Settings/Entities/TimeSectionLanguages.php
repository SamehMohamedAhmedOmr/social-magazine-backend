<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TimeSectionLanguages extends Pivot
{
    protected $fillable = [
        'time_section_id',
        'language_id',
        'name',
    ];
}
