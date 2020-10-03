<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Language;

class BrandLanguage extends Model
{
    protected $fillable = ['brand_id', 'language_id'];
    protected $table = 'brand_language';

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
