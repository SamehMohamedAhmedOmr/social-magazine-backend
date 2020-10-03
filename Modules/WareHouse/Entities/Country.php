<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Settings\Entities\Language;

class Country extends Model
{
    use SoftDeletes;
    protected $fillable = [
        // properties
        'country_code' ,
        'is_active' ,
        'image'
    ];

    public function language()
    {
        return $this->belongsToMany(Language::class, 'country_languages')
            ->using(CountryLanguage::class)
            ->withPivot('name', 'description');
    }

    public function currentLanguage()
    {
        return $this->hasOne(CountryLanguage::class, 'country_id')
            ->where('language_id', \Session::get('language_id'));
    }

    public function district()
    {
        return $this->hasMany(District::class, 'country_id', 'id');
    }

}
