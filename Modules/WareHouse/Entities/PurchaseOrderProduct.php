<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PurchaseOrderProduct extends Pivot
{
    protected $table = 'purchase_orders_products';

    protected $fillable = ['purchase_order_id','product_id','quantity','price','total_amount'];
}
