<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;
use Modules\Catalogue\Entities\Product;
use Modules\Settings\Entities\Company;
use Modules\Settings\Entities\ShippingRules;
use Modules\Settings\Entities\TaxesList;

class PurchaseReceipt extends Model
{
    use SoftDeletes;
    protected $table = 'purchase_receipts';
    protected $fillable = [
        'purchase_order_id','company_id','shipping_rule_id','tax_id','status','country_id'
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


    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_receipt_products', 'purchase_receipt_id', 'product_id')
            ->using(PurchaseReceiptProduct::class)
            ->withPivot(
                'purchase_receipt_id',
                'product_id',
                'requested_quantity',
                'remaining_quantity',
                'accepted_quantity'
            );
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function purchaseInvoice()
    {
        return $this->hasMany(PurchaseInvoice::class, 'purchase_receipt_id', 'id');
    }

    public function tax()
    {
        return $this->belongsTo(TaxesList::class, 'tax_id', 'id');
    }

    public function shippingRule()
    {
        return $this->belongsTo(ShippingRules::class, 'shipping_rule_id', 'id');
    }
}
