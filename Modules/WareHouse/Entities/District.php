<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;
use Illuminate\Support\Facades\Session;
use Modules\Settings\Entities\Language;
use Modules\Settings\Entities\ShippingRules;
use Modules\Users\Entities\Address;

class District extends Model
{
    use SoftDeletes;

    protected $table = 'districts';
    protected $fillable = [
        //  foreign keys
        'country_id',
        'shipping_role_id' ,
        'parent_id',
        // properties
        'is_active' ,
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

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function shippingRule()
    {
        return $this->belongsTo(ShippingRules::class, 'shipping_role_id', 'id');
    }

    public function parentDistrict()
    {
        return $this->belongsTo(District::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(District::class, 'parent_id', 'id');
    }

    public function language()
    {
        return $this->belongsToMany(Language::class, 'district_languages')
            ->using(DistrictLanguage::class)
            ->withPivot('name', 'description');
    }

    public function currentLanguage()
    {
        return $this->hasOne(DistrictLanguage::class, 'district_id')
            ->where('language_id', Session::get('language_id'));
    }

    public function warehouse()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_district',
            'district_id', 'warehouse_id')
            ->using(WarehouseDistrict::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'district_id');
    }
}
