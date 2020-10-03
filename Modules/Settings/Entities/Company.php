<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Base\Scopes\CountryScope;
use Modules\Gallery\Entities\Gallery;

class Company extends Model
{
    protected $table = 'companies';
    protected $fillable = ['logo','country_id'];

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
        return $this->belongsToMany(Language::class, 'company_language')
            ->using(CompanyLanguage::class)->withPivot('name', 'description');
    }

    public function currentLanguage()
    {
        return $this->hasOne(CompanyLanguage::class, 'company_id')
            ->where('language_id', \Session::get('language_id'));
    }


    public function companyImg()
    {
        return $this->hasOne(Gallery::class, 'id','logo');
    }
}
