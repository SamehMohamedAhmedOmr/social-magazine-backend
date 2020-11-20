<?php

namespace Modules\PreArticle\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceType extends Model
{
    use SoftDeletes;

    protected $table = 'price_type';

    protected $fillable = [
        'name' , 'key'
    ];
}
