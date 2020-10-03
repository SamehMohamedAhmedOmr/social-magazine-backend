<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Language;

class CategoryLanguage extends Model
{
    protected $fillable = ['category_id', 'language_id'];
    protected $table = 'category_language';
    protected $nullable = [];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
