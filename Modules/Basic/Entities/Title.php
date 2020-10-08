<?php

namespace Modules\Basic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use SoftDeletes;
    protected $table = 'titles';
    protected $fillable = ['name', 'key'];


}
