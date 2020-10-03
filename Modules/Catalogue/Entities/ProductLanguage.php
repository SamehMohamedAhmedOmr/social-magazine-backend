<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Settings\Entities\Language;

class ProductLanguage extends Model
{
    protected $table = 'product_language';
    protected $fillable = [
        'product_id', 'language_id',
        'name', 'description', 'slug',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
