<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MagazineNewsImages extends Pivot
{
    protected $table = 'magazine_news_images';
    protected $fillable = [
        'news_id' , 'image_id'
    ];



}
