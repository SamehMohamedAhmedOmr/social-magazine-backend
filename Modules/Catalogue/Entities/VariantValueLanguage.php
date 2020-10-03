<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Language;

class VariantValueLanguage extends Model
{
    protected $fillable = ['language_id', 'variant_value_id'];
    protected $table = 'variant_value_language';

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function variantValue()
    {
        return $this->belongsTo(VariantValue::class, 'variant_value_id');
    }
}
