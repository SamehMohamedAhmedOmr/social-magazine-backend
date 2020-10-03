<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Modules\Base\Scopes\CountryScope;
use Modules\Catalogue\Entities\Product;
use Modules\Settings\Entities\Language;
use Modules\Users\Entities\Admin;

class Warehouse extends Model
{
    use SoftDeletes;
    protected $table = 'warehouses';
    protected $fillable = ['warehouse_code','is_active','default_warehouse' , 'country_id'];


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
        return $this->belongsToMany(
            Language::class,
            'warehouse_languages',
            'warehouse_id',
            'language_id'
        )
            ->using(WarehouseLanguage::class)
            ->withPivot('name', 'description');
    }

    public function currentLanguage()
    {
        return $this->hasOne(WarehouseLanguage::class, 'warehouse_id')
            ->where('language_id', Session::get('language_id'));
    }

    public function district()
    {
        return $this->belongsToMany(District::class, 'warehouse_district',
            'warehouse_id', 'district_id')
            ->using(WarehouseDistrict::class);
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_warehouses', 'warehouse_id', 'product_id')
            ->using(ProductWarehouse::class)
            ->withPivot('product_id', 'warehouse_id', 'projected_quantity', 'available');
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_warehouses', 'warehouse_id', 'admin_id')
            ->using(AdminWarehouses::class);
    }
}
