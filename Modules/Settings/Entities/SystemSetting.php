<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Base\Scopes\CountryScope;

class SystemSetting extends Model
{
    protected $table = 'system_settings';
    protected $fillable = [
        'minimum_selling_price','maximum_selling_price','is_free_shipping','free_shipping_minimum_price', 'country_id'
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
}
