<?php

namespace Modules\WareHouse\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Catalogue\Entities\Product;

class ProductWarehouse extends Pivot
{
    protected $table = 'product_warehouses';
    protected $fillable = ['product_id', 'warehouse_id', 'projected_quantity', 'available'];
    public $incrementing = false;
    protected $primaryKey = null;

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
