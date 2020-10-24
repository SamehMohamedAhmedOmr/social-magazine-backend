<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MagazineCategoryImages extends Pivot
{
    protected $table = 'magazine_category_images';
    protected $fillable = [
        'category_id' , 'image_id'
    ];

}
