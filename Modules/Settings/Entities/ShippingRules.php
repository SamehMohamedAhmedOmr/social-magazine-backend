<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;

class ShippingRules extends Model
{
    use SoftDeletes;

    protected $fillable = ['shipping_rule_label','price','key','is_active', 'country_id'];

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

    public function district()
    {
        // TODO
    }
}
