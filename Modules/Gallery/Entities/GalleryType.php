<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Model;

class GalleryType extends Model
{
    protected $table = 'gallery_types';
    protected $fillable = ['name', 'key', 'folder'];

    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'gallery_type_id', 'id');
    }
}
