<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Modules\Base\Scopes\CountryScope;

class UnitOfMeasure extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'units_of_measure';

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

    // Relation For CMS
    public function languages()
    {
        return $this->hasMany(UnitOfMeasureLanguage::class, 'unit_of_measure_id');
    }

    // Relation For Front
    public function currentLanguage()
    {
        return $this->hasOne(UnitOfMeasureLanguage::class, 'unit_of_measure_id')
            ->where('language_id', Session::get('language_id'));
    }
}
