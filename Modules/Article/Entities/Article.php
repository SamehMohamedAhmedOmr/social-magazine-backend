<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PreArticle\Entities\ArticleSubject;
use Modules\PreArticle\Entities\ArticleType;
use Modules\Users\Entities\User;

class Article extends Model
{
    use SoftDeletes;
    protected $table = 'articles';

    protected $fillable = [
        'article_code', 'title_ar', 'title_en', 'slug',
        'content_ar', 'content_en',
        'article_subject_id', 'author_id', 'article_type_id',
        'review_date', 'acceptance_date',
        'keywords_en', 'keywords_ar',
        'watched', 'downloaded'
    ];

    protected $casts = [
        'keywords_en' => 'array',
        'keywords_ar' => 'array',
    ];

    public function subject(){
        return $this->belongsTo(ArticleSubject::class,'article_subject_id','id');
    }

    public function type(){
        return $this->belongsTo(ArticleType::class,'article_type_id','id');
    }

    public function mainAuthor(){
        return $this->belongsTo(User::class,'author_id','id');
    }

    /* Thing Belongs to Article */

    public function attachment(){
        return $this->hasMany(ArticleAttachment::class,'article_id','id');
    }

    public function authors(){
        return $this->hasMany(ArticleAuthors::class,'article_id','id');
    }

    public function selectedJudge(){
        return $this->hasMany(ArticleSelectedJudge::class,'article_id','id');
    }

    public function suggestedJudge(){
        return $this->hasMany(ArticleSuggestedJudge::class,'article_id','id');
    }

    public function statusHistory(){
        return $this->hasMany(ArticleStatus::class,'article_id','id');
    }

    public function lastStatus(){
        return $this->hasMany(ArticleStatus::class,'article_id','id')->latest();
    }

}
