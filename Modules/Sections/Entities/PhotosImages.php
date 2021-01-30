<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PhotosImages extends Pivot
{
    protected $table = 'photos_images';
    protected $fillable = [
        'photo_id' , 'image_id'
    ];



}
