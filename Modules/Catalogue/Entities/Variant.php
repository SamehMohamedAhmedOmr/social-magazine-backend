<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Variant extends Model
{
    use SoftDeletes;
    protected $fillable = ['is_active', 'is_color'];

    // Relation For CMS
    public function languages()
    {
        return $this->hasMany(VariantLanguage::class, 'variant_id');
    }

    // Relation For Front
    public function currentLanguage()
    {
        return $this->hasOne(VariantLanguage::class, 'variant_id')
            ->where('language_id', Session::get('language_id'));
    }

    public function values()
    {
        return $this->hasMany(VariantValue::class, 'variant_id');
    }
}
