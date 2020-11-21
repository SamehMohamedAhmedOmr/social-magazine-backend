<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleStatus extends Model
{
    use SoftDeletes;
    protected $table = 'article_status';

    protected $fillable = [
        'article_id', 'status_id', 'review_date', 'judgement_date',
        'magazine_director_id', 'magazine_manager_note', 'price_type_id', 'payment_method_id',
        'currency_type_id', 'fees'
    ];


}
