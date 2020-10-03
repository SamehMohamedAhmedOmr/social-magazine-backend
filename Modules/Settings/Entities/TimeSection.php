<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;

class TimeSection extends Model
{
    use SoftDeletes;
    protected $table = 'time_sections';
    protected $fillable = [
        'from',
        'to',
        'is_active',
        'country_id'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CountryScope);
    }


    public function language()
    {
        return $this->belongsToMany(Language::class, 'time_section_languages')
            ->using(TimeSectionLanguages::class)
            ->withPivot('name');
    }

    public function days()
    {
        return $this->belongsToMany(Days::class, 'time_section_days', 'time_section_id', 'day_id')
            ->using(TimeSectionDays::class)
            ->withPivot('day_id', 'time_section_id');
    }
}
