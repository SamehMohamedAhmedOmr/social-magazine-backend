<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MagazineGoals extends Model
{
    use SoftDeletes;
    protected $table = 'magazine_goals';
    protected $fillable = [
        'content', 'is_active'
    ];

}
