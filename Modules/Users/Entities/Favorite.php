<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Catalogue\Entities\Product;

class Favorite extends Model
{
    use SoftDeletes;

    protected $table = 'favorites';
    protected $fillable = ['product_name','product_code','user_id','product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
