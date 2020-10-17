<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhoIsUs extends Model
{
    use SoftDeletes;
    protected $table = 'who_is_us';
    protected $fillable = [
        'content', 'is_active'
    ];


}
