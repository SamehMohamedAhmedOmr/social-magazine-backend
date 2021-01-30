<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PreArticle\Entities\ArticleStatusList;
use Modules\PreArticle\Entities\AttachmentType;
use Modules\Users\Entities\User;

class ArticleAttachment extends Model
{
    use SoftDeletes;
    protected $table = 'article_attachment';

    protected $fillable = [
        'title', 'file', 'article_id', 'status_id',
        'attachment_type_id', 'uploaded_by', 'description'
    ];

    protected $with = [
        'attachmentType',
        'uploadBy'
    ];

    public function attachmentType(){
        return $this->belongsTo(AttachmentType::class,'attachment_type_id','id');
    }

    public function uploadBy(){
        return $this->belongsTo(User::class,'uploaded_by','id');
    }

    public function status(){
        return $this->belongsTo(ArticleStatusList::class,'status_id','id');
    }
}
