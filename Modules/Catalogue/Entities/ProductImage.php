<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductImage extends Pivot
{
    protected $table = 'product_images';
    protected $fillable = [
        'product_id', 'image', 'order'
    ];
}
