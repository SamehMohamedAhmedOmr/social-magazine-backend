<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;

class PurchaseInvoice extends Model
{
    use SoftDeletes;
    protected $table = 'purchase_invoices';
    protected $fillable = [
        'purchase_receipt_id' ,'status','total_price', 'country_id'
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


    public function purchaseReceipt()
    {
        return $this->belongsTo(PurchaseReceipt::class, 'purchase_receipt_id', 'id');
    }

    public function paymentEntry()
    {
        return $this->hasMany(PaymentEntry::class, 'purchase_invoice_id', 'id');
    }
}
