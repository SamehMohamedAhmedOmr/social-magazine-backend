<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleAuthors extends Model
{
    use SoftDeletes;
    protected $table = 'article_authors';

    protected $fillable = [
        'first_name', 'family_name', 'email', 'alternative_email',
        'gender_id', 'title_id', 'educational_level_id', 'educational_degree_id',
        'phone_number', 'address', 'country_id', 'article_id'
    ];

}
