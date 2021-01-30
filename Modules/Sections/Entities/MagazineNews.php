<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Facade\UtilitiesHelper;
use Modules\Gallery\Entities\Gallery;

class MagazineNews extends Model
{
    use SoftDeletes;
    protected $table = 'magazine_news';
    protected $fillable = [
        'title' , 'slug',
        'content' , 'views' , 'is_active'
    ];

    public function getCreatedAtAttribute($value)
    {
        return UtilitiesHelper::dateShape($value);
    }

    public function images()
    {
        return $this->belongsToMany(
            Gallery::class,
            'magazine_news_images',
            'news_id',
            'image_id'
        )->using(MagazineNewsImages::class);
    }
}
