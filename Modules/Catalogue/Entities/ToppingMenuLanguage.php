<?php

namespace Modules\Catalogue\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Language;

class ToppingMenuLanguage extends Model
{
    protected $fillable = ['language_id', 'topping_menu_id'];
    protected $table = 'topping_menu_language';

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function toppingMenu()
    {
        return $this->belongsTo(ToppingMenu::class, 'topping_menu_id');
    }
}
