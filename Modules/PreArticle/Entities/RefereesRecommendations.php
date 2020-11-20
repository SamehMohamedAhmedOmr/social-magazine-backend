<?php

namespace Modules\PreArticle\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefereesRecommendations extends Model
{
    use SoftDeletes;

    protected $table = 'referees_recommendation';

    protected $fillable = [
        'name' , 'key'
    ];
}
