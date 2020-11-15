<?php

namespace Modules\PreArticle\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleFilter extends Model
{
    use SoftDeletes;
    protected $table = 'article_filter';
    protected $fillable = [
        'name' , 'key'
    ];
}
