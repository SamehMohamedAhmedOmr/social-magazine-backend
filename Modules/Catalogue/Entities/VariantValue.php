<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class VariantValue extends Model
{
    use SoftDeletes;
    protected $table = 'variant_values';
    protected $fillable = ['code', 'value', 'palette_image', 'variant_id', 'is_active'];

    // Relation For CMS
    public function languages()
    {
        return $this->hasMany(VariantValueLanguage::class, 'variant_value_id');
    }

    // Relation For Front
    public function currentLanguage()
    {
        return $this->hasOne(VariantValueLanguage::class, 'variant_value_id')
            ->where('language_id', Session::get('language_id'));
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }
}
