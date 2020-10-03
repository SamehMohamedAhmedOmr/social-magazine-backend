<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;
use Modules\Catalogue\Entities\Product;
use Modules\Settings\Entities\Company;
use Modules\Settings\Entities\ShippingRules;
use Modules\Settings\Entities\TaxesList;

class PurchaseOrder extends Model
{
    use SoftDeletes;
    protected $table = 'purchase_orders';

    protected $fillable = [
        'delivery_date','discount','discount_type','warehouse_id','company_id',
        'shipping_rule_id','tax_id','is_active','total_price','country_id'
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



    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_orders_products', 'purchase_order_id', 'product_id')
            ->using(PurchaseOrderProduct::class)
            ->withPivot('purchase_order_id', 'product_id', 'quantity', 'price', 'total_amount');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function tax()
    {
        return $this->belongsTo(TaxesList::class, 'tax_id', 'id');
    }

    public function shippingRule()
    {
        return $this->belongsTo(ShippingRules::class, 'shipping_rule_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function PurchaseReceipt()
    {
        return $this->hasMany(PurchaseReceipt::class, 'purchase_order_id', 'id');
    }
}
