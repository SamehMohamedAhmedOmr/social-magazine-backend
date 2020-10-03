<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductVariantValue extends Model
{
    protected $fillable = ['product_id', 'variant_value_id'];
    protected $table = 'product_variant_value';

    public function value()
    {
        return $this->belongsTo(VariantValue::class, 'variant_value_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
