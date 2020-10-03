<?php

namespace Modules\FrontendUtilities\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;
use Modules\Catalogue\Entities\Product;
use Modules\Settings\Entities\Language;

class Collection extends Model
{
    use SoftDeletes;
    protected $table = 'collection';
    protected $fillable = [
        'order' ,
        'is_active' ,
        'enable_ios' ,
        'enable_android' ,
        'enable_web',
        'country_id'
    ];

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

    public function language()
    {
        return $this->belongsToMany(Language::class, 'collection_languages')
            ->using(CollectionLanguage::class)
            ->withPivot('title');
    }

    public function currentLanguage()
    {
        return $this->hasOne(CollectionLanguage::class, 'collection_id')
            ->where('language_id', \Session::get('language_id'));
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'collection_products',
            'collection_id',
            'product_id'
        )
            ->using(CollectionProduct::class);
    }
}
