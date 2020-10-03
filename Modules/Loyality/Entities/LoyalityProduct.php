<?php

namespace Modules\Loyality\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Catalogue\Entities\Product;

class LoyalityProduct extends Model
{
    protected $table = 'loyality_products';
    protected $fillable = ['product_id' , 'weight', 'is_active'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
