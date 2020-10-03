<?php

namespace Modules\FrontendUtilities\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Settings\Entities\Language;

class CollectionLanguage extends Pivot
{
    protected $table = 'collection_languages';

    protected $fillable = [
        'language_id' ,
        'collection_id' ,
        'title'
    ];

}
