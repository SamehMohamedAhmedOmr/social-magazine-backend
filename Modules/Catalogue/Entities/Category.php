<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Modules\Base\Scopes\CountryScope;
use Modules\Gallery\Entities\Gallery;

class Category extends Model
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
        return $this->hasMany(CategoryLanguage::class, 'category_id');
    }

    // Relation For Front
    public function currentLanguage()
    {
        return $this->hasOne(CategoryLanguage::class, 'category_id')
            ->where('language_id', Session::get('language_id'));
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function child()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'main_category_id');
    }

    public function otherProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'product_category',
            'category_id',
            'product_id'
        );
    }

    public function categoryImg()
    {
        return $this->hasOne(Gallery::class, 'id','icon');
    }

    public function categoryIcon()
    {
        return $this->hasOne(Gallery::class, 'id','image');
    }

}
