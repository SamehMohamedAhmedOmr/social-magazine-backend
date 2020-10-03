<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PurchaseReceiptProduct extends Pivot
{
    protected $table = 'purchase_receipt_products';
    protected $fillable = [
        'purchase_receipt_id','product_id',
        'requested_quantity','remaining_quantity','accepted_quantity'
    ];
}
