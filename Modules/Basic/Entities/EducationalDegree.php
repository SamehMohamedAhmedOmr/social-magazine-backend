<?php

namespace Modules\Basic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationalDegree extends Model
{
    use SoftDeletes;
    protected $table = 'educational_degrees';
    protected $fillable = ['name', 'key'];


}
