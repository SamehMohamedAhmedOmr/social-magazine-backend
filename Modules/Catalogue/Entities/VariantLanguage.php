<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Language;

class VariantLanguage extends Model
{
    protected $fillable = ['language_id', 'variant_id'];
    protected $table = 'variant_language';

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }
}
