<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Gallery\Entities\Gallery;

class MagazineCategory extends Model
{
    use SoftDeletes;
    protected $table = 'magazine_category';
    protected $fillable = ['content' , 'is_active'];

    public function images()
    {
        return $this->belongsToMany(
            Gallery::class,
            'magazine_category_images',
            'category_id',
            'image_id'
        )->using(MagazineCategoryImages::class);
    }
}
