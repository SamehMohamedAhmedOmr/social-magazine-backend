<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PreArticle\Entities\ArticleStatusList;

class ArticleStatus extends Model
{
    use SoftDeletes;
    protected $table = 'article_status';

    protected $fillable = [
        'article_id', 'status_id', 'review_date', 'judgement_date',
        'magazine_director_id', 'magazine_manager_note', 'price_type_id', 'payment_method_id',
        'currency_type_id', 'fees'
    ];

    protected $with = [
        'status',
    ];

    public function status()
    {
        return $this->belongsTo(ArticleStatusList::class, 'status_id', 'id');
    }


}
