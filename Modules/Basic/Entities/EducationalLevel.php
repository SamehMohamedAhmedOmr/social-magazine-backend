<?php

namespace Modules\Basic\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationalLevel extends Model
{
    use SoftDeletes;
    protected $table = 'educational_levels';
    protected $fillable = ['name'];


}
