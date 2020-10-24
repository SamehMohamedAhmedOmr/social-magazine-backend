<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Gallery\Entities\Gallery;

class Testimonial extends Model
{
    use SoftDeletes;
    protected $table = 'magazine_testimonial';
    protected $fillable = [
        'name' , 'content' ,
        'stars' ,
        'image_id' , 'is_active'
    ];

    public function image()
    {
        return $this->hasOne(Gallery::class, 'id','image_id');
    }

}
