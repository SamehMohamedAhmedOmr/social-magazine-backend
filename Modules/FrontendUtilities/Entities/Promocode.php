<?php

namespace Modules\FrontendUtilities\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Base\Scopes\CountryScope;
use Modules\Catalogue\Entities\Brand;
use Modules\Catalogue\Entities\Category;
use Modules\Catalogue\Entities\Product;
use Modules\Users\Entities\User;

class Promocode extends Model
{
    protected $table = 'promocodes';
    protected $fillable = [
        'code',
        'minimum_price',
        'maximum_price',
        'is_free_shipping',
        'discount_type',
        'users_count',
        'from',
        'to',
        'is_active',
        'reward',
        'max_discount_amount',
        'usage_per_user',
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

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_promocode', 'promocode_id', 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_promocode', 'promocode_id', 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_promocode', 'promocode_id', 'category_id');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_promocode', 'promocode_id', 'brand_id');
    }

    public function usage()
    {
        return $this->hasMany(PromocodeUsage::class, 'promocode_id', 'id');
    }
}
