<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Facade\UtilitiesHelper;
use Modules\Gallery\Entities\Gallery;

class Photos extends Model
{
    use SoftDeletes;
    protected $table = 'photos';
    protected $fillable = [
        'title' , 'slug',
        'content' , 'is_active'
    ];

    public function getCreatedAtAttribute($value)
    {
        return UtilitiesHelper::dateShape($value);
    }

    public function images()
    {
        return $this->belongsToMany(
            Gallery::class,
            'photos_images',
            'photo_id',
            'image_id'
        )->using(PhotosImages::class);
    }
}
