<?php

namespace Modules\PreArticle\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyType extends Model
{
    use SoftDeletes;

    protected $table = 'currency_type';

    protected $fillable = [
        'name' , 'key'
    ];
}
