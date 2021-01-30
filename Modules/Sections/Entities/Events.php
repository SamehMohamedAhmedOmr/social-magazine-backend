<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Facade\UtilitiesHelper;
use Modules\Gallery\Entities\Gallery;

class Events extends Model
{
    use SoftDeletes;
    protected $table = 'events';
    protected $fillable = [
        'title' , 'slug',
        'content' , 'date' , 'is_active'
    ];

    public function images()
    {
        return $this->belongsToMany(
            Gallery::class,
            'events_images',
            'event_id',
            'image_id'
        )->using(EventsImages::class);
    }
}
