<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleSuggestedJudge extends Model
{
    use SoftDeletes;
    protected $table = 'article_suggested_judge';

    protected $fillable = [
        'first_name', 'family_name', 'email', 'alternative_email',
        'gender_id', 'title_id', 'educational_level_id', 'educational_degree_id',
        'phone_number', 'address', 'country_id', 'article_id'
    ];

    public function attachment(){
        return $this->belongsToMany(ArticleAttachment::class,'selected_judge_attachment',
            'selected_judge_id','attachment_id')
            ->using(SelectedJudgeAttachment::class);
    }

}
