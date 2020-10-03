<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PagesLanguages extends Pivot
{
    protected $table = 'pages_languages';
    protected $fillable = [
        'page_id', 'language_id',
        'title', 'content',
        'seo_title' , 'seo_description'
    ];
}
