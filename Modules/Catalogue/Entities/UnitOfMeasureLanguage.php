<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Language;

class UnitOfMeasureLanguage extends Model
{
    protected $fillable = ['language_id', 'unit_of_measure_id'];
    protected $table = 'unit_of_measure_language';
    protected $nullable = [];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function unitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_of_measure_id');
    }
}
