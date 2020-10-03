<?php

namespace Modules\FrontendUtilities\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CollectionProduct extends Pivot
{
    protected $table = 'collection_products';
    protected $fillable = ['collection_id', 'product_id'];
}
