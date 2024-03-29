<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleSelectedJudge extends Model
{
    use SoftDeletes;
    protected $table = 'article_selected_judge';
    protected $fillable = [
        'article_id', 'judge_id', 'recommendation_id',
        'author_note', 'magazine_note'
    ];


    public function attachment(){
        return $this->belongsToMany(ArticleAttachment::class,'selected_judge_attachment',
            'selected_judge_id','attachment_id')
            ->using(SelectedJudgeAttachment::class);
    }

}
