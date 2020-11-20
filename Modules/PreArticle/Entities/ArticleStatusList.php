<?php

namespace Modules\PreArticle\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleStatusList extends Model
{
    use SoftDeletes;
    protected $table = 'article_status_list';
    protected $fillable = [
        'name' , 'description', 'key', 'type_id'
    ];

    public function type(){
        return $this->belongsTo(ArticleStatusType::class,'type_id','id');
    }

}
