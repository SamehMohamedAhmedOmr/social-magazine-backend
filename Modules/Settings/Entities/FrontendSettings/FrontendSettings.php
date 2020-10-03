<?php

namespace Modules\Settings\Entities\FrontendSettings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;
use Modules\Gallery\Entities\Gallery;
use Modules\Settings\Entities\Language;

class FrontendSettings extends Model
{
    use SoftDeletes;
    protected $table = 'frontend_settings';
    protected $fillable = [
        'favicon' , 'logo' , 'social_sharing_img',
        'google_analytics_id', 'facebook_pixel_id' , 'enable_recaptcha',
        'app_nav_structure_id',
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

    public function colors(){
        return $this->belongsTo(FrontendColors::class,'id','frontend_setting_id');
    }

    public function menu(){
        return $this->hasMany(FrontendMenu::class,'frontend_setting_id','id')
            ->orderBy('order');
    }

    public function typography(){
        return $this->belongsTo(FrontendTypography::class,'id','frontend_setting_id');
    }

    public function socialMedia(){
        return $this->belongsTo(FrontendSocialMedia::class,'id','frontend_setting_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'frontend_settings_languages',
            'setting_id','language_id')
            ->using(FrontendSettingsLanguages::class)->withPivot('home_page_title', 'home_page_meta_desc');
    }

    public function currentLanguage()
    {
        return $this->hasOne(FrontendSettingsLanguages::class, 'setting_id')
            ->where('language_id', \Session::get('language_id'));
    }

    public function faviconImg()
    {
        return $this->hasOne(Gallery::class, 'id','favicon');
    }

    public function socialSharingImg()
    {
        return $this->hasOne(Gallery::class, 'id','social_sharing_img');
    }

    public function logoImg()
    {
        return $this->hasOne(Gallery::class, 'id','logo');
    }

    public function applicationNavigationStructure()
    {
        return $this->hasOne(ApplicationNavigationStructure::class, 'id','app_nav_structure_id');
    }

}
