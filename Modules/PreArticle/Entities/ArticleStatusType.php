<?php

namespace Modules\PreArticle\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleStatusType extends Model
{
    use SoftDeletes;
    protected $table = 'article_status_type';
    protected $fillable = [
        'name' , 'key'
    ];
}
