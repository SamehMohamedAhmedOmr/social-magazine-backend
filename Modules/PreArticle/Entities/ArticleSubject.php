<?php

namespace Modules\PreArticle\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleSubject extends Model
{
    use SoftDeletes;

    protected $table = 'article_subject';

    protected $fillable = [
        'name' , 'key'
    ];

}
