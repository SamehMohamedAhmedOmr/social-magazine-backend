<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Base\Scopes\CountryScope;

class Currency extends Model
{
    protected $table = 'currency';
    protected $fillable = ['key','name','country_id'];

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
}
