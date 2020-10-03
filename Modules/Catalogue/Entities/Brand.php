<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Modules\Base\Scopes\CountryScope;
use Modules\Gallery\Entities\Gallery;

class Brand extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CountryScope);
    }

    // Relation For CMS
    public function languages()
    {
        return $this->hasMany(BrandLanguage::class, 'brand_id');
    }

    // Relation For Front
    public function currentLanguage()
    {
        return $this->hasOne(BrandLanguage::class, 'brand_id')
            ->where('language_id', Session::get('language_id'));
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    public function brandImg()
    {
        return $this->hasOne(Gallery::class, 'id','icon');
    }
}
