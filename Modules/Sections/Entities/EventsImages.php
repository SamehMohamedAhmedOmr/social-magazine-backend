<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventsImages extends Pivot
{
    protected $table = 'events_images';
    protected $fillable = [
        'event_id' , 'image_id'
    ];



}
