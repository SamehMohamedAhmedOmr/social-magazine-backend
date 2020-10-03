<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 */
class Days extends Model
{
    protected $table = 'days';

    public function timeSections()
    {
        return $this->belongsToMany(TimeSection::class, 'time_section_days', 'day_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'days_languages', 'day_id')
            ->using(DaysLanguages::class)
            ->withPivot('name');
    }
}
