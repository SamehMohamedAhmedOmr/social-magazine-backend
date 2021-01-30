<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Facade\UtilitiesHelper;
use Modules\Gallery\Entities\Gallery;

class Video extends Model
{
    use SoftDeletes;
    protected $table = 'videos';
    protected $fillable = [
        'title' , 'slug',
        'content',
        'link' , 'is_active'
    ];

    public function getCreatedAtAttribute($value)
    {
        return UtilitiesHelper::dateShape($value);
    }

}
