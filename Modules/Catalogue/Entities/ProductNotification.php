<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductNotification extends Pivot
{
    protected $table = 'product_notification';

    protected $fillable = ['product_id', 'user_id', 'warehouse_id'];

}
