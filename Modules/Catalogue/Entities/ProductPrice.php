<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\PriceList;

class ProductPrice extends Model
{
    protected $table = 'product_price_list';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function priceList()
    {
        return $this->belongsTo(PriceList::class, 'price_list_id');
    }
}
