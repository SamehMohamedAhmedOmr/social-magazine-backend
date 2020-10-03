<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pages extends Model
{
    use SoftDeletes;

    protected $table = 'pages';

    protected $fillable = [
        'page_url' , 'is_active'
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'pages_languages',
            'page_id','language_id')
            ->using(PagesLanguages::class)
            ->withPivot('title', 'content', 'seo_title' , 'seo_description');
    }

    public function currentLanguage()
    {
        return $this->hasOne(PagesLanguages::class, 'page_id')
            ->where('language_id', \Session::get('language_id'));
    }

}
