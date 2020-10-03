<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class ToppingMenu extends Model
{
    use SoftDeletes;
    protected $table = 'topping_menus';
    protected $fillable = ['is_active'];

    // Relation For CMS
    public function languages()
    {
        return $this->hasMany(ToppingMenuLanguage::class, 'topping_menu_id');
    }

    // Relation For Front
    public function currentLanguage()
    {
        return $this->hasOne(ToppingMenuLanguage::class, 'topping_menu_id')
            ->where('language_id', Session::get('language_id'));
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_topping_menu',
            'topping_menu_id',
            'product_id'
        );
    }
}
