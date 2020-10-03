<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Modules\FrontendUtilities\Entities\Collection;
use Modules\Gallery\Entities\Gallery;
use Modules\Settings\Entities\PriceList;
use Modules\Users\Entities\Favorite;
use Modules\Users\Entities\User;
use Modules\WareHouse\Entities\Order\Order;
use Modules\WareHouse\Entities\ProductWarehouse;
use Modules\WareHouse\Entities\Stock;
use Modules\WareHouse\Entities\Warehouse;
use Spatie\Tags\HasTags;

class Product extends Model
{
    use SoftDeletes, HasTags;

    protected $fillable = [
        'weight', 'sku',
        'main_category_id', 'brand_id', 'unit_of_measure_id', 'parent_id',
        'is_topping', 'is_bundle', 'is_variant', 'is_active', 'topping_menu_id',
        'dynamic_link', 'max_quantity_per_order', 'is_sell_with_availability'
    ];

    // Relation For CMS
    public function languages()
    {
        return $this->hasMany(ProductLanguage::class, 'product_id');
    }

    // Relation For Front
    public function currentLanguage()
    {
        return $this->hasOne(ProductLanguage::class, 'product_id')
            ->where('language_id', Session::get('language_id'));
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function mainCategory()
    {
        return $this->belongsTo(Category::class, 'main_category_id');
    }

    public function unitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_of_measure_id');
    }

    public function toppingMenu()
    {
        return $this->belongsTo(ToppingMenu::class, 'topping_menu_id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'product_category',
            'product_id',
            'category_id'
        );
    }

    public function variantValues()
    {
        return $this->belongsToMany(
            VariantValue::class,
            'product_variant_value',
            'product_id',
            'variant_value_id'
        );
    }

    public function priceLists()
    {
        return $this->belongsToMany(
            PriceList::class,
            'product_price_list',
            'product_id',
            'price_list_id'
        )->withPivot('price');
    }

    public function price()
    {
        return $this->priceLists()->where('price_lists.key', 'STANDARD_SELLING_PRICE')
            ->withPivot('price');
    }

    public function buyingPrice()
    {
        return $this->priceLists()
            ->where('price_lists.key', 'STANDARD_BUYING_PRICE')
            ->withPivot('price');
    }

    public function variations()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function variantTo()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id', 'id');
    }

    public function warehouses()
    {
        return $this->hasMany(ProductWarehouse::class, 'product_id', 'id');
    }

    public function collectionsRelation()
    {
        return $this->belongsTo(
            Collection::class,
            'collection_products',
            'product_id',
            'collection_id'
        );
    }

    public function images()
    {
        return $this->belongsToMany(
            Gallery::class,
            'product_images',
            'product_id',
            'image'
        )->withPivot('order')->using(ProductImage::class)->orderBy('order');
    }

    public function availableWarehouses()
    {
        return $this->belongsToMany(
            Warehouse::class,
            'product_warehouses',
            'product_id',
            'warehouse_id'
        )->withPivot('projected_quantity')->using(ProductWarehouse::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(Stock::class, 'product_id', 'id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items');
    }

    public function inStockWarehouse()
    {
        return $this->hasOne(ProductWarehouse::class,'product_id')
            ->whereIn('warehouse_id', Session::get('in_stock_warehouses'));
    }

    public function usersSubscription()
    {
        return $this->belongsToMany(
            User::class,
            'product_notification',
            'product_id',
            'user_id'
        )->withPivot('warehouse_id')->using(ProductNotification::class);
    }


}
