<?php

namespace Modules\FrontendUtilities\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Scopes\CountryScope;
use Modules\Gallery\Entities\Gallery;
use Modules\Settings\Entities\Language;

class Banner extends Model
{
    use SoftDeletes;

    protected $table = 'banners';
    protected $fillable = [
        'link',
        'image',
        'enable_ios',
        'enable_android',
        'enable_web',
        'is_active' ,
        'order' ,
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
        return $this->belongsToMany(Language::class, 'banners_languages')
            ->using(BannerLanguages::class)
            ->withPivot('title', 'description', 'alternative', 'subject_1', 'subject_2');
    }

    public function bannerImg()
    {
        return $this->hasOne(Gallery::class, 'id','image');
    }

    public function currentLanguage()
    {
        return $this->hasOne(BannerLanguages::class, 'banner_id')
            ->where('language_id', \Session::get('language_id'));
    }

}
