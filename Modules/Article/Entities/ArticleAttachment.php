<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleAttachment extends Model
{
    use SoftDeletes;
    protected $table = 'article_attachment';

    protected $fillable = [
        'file', 'article_id', 'status_id',
        'attachment_type_id', 'uploaded_by', 'description'
    ];

}
