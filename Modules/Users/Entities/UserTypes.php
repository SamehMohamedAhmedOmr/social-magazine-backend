<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class UserTypes extends Model
{
    protected $table = 'user_types';
    protected $fillable = [
        'name','key'
    ];
}
