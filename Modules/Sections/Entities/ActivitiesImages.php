<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ActivitiesImages extends Pivot
{
    protected $table = 'activities_images';
    protected $fillable = [
        'activity_id' , 'image_id'
    ];



}
