<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvisoryBody extends Model
{
    use SoftDeletes;
    protected $table = 'advisory_body';
    protected $fillable = [
        'name' , 'job' , 'is_active'
    ];

}
