<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;
use Modules\WareHouse\Entities\Country;

class PriceList extends Model
{
    use SoftDeletes;

    protected $table = 'price_lists';
    protected $fillable = [
        'country_id','currency_code','price_list_name','type',
        'key','is_special','is_active', 'country_id'
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

    public function PriceListType()
    {
        return $this->belongsTo(PriceListType::class, 'type', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'id');
    }
}
