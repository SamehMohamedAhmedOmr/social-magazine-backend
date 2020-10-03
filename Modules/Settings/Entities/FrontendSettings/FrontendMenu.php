<?php

namespace Modules\Settings\Entities\FrontendSettings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Settings\Entities\Language;

class FrontendMenu extends Model
{
    use SoftDeletes;

    protected $table = 'frontend_menu';

    protected $fillable = [
        'key' , 'order',
        'navigation_type_id', 'frontend_setting_id'
    ];

    public function FrontendSettings(){
        return $this->belongsTo(FrontendSettings::class,'id','frontend_setting_id');
    }

    public function navigationMenu(){
        return $this->belongsTo(FrontendMenuNavigationType::class,'navigation_type_id','id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'frontend_menu_languages',
            'menu_id','language_id')
            ->using(FrontendMenuLanguages::class)->withPivot('name');
    }

    public function currentLanguage()
    {
        return $this->hasOne(FrontendMenuLanguages::class, 'menu_id')
            ->where('language_id', \Session::get('language_id'));
    }

}
