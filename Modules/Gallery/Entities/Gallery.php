<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'gallery';
    protected $fillable = [
        'image', 'thumbnail', 'gallery_type_id'
    ];

    public function galleryType()
    {
        return $this->belongsTo(GalleryType::class, 'gallery_type_id', 'id');
    }
}
