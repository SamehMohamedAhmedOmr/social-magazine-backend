<?php

namespace Modules\FrontendUtilities\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BannerLanguages extends Pivot
{
    protected $table = 'banners_languages';
    protected $fillable = [
        'banner_id',
        'language_id',
        'title',
        'description' ,
        'alternative',
        'subject_1',
        'subject_2',
    ];
}
