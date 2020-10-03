<?php

namespace Modules\Loyality\Entities;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    protected $table = 'users_points';
    protected $fillable = [
        'user_id', 'points',
    ];
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $hidden = ['updated_at'];
}
