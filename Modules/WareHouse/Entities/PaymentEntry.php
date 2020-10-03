<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;

class PaymentEntry extends Model
{
    use SoftDeletes;
    protected $table = 'payment_entries';
    protected $fillable = [
        'purchase_invoice_id','payment_price',
        'payment_reference','payment_entry_type_id','status',
        'country_id'
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


    public function PaymentEntryType()
    {
        return $this->belongsTo(PaymentEntryType::class, 'payment_entry_type_id', 'id');
    }

    public function PurchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id', 'id');
    }
}
