<?php

namespace Modules\Loyality\Entities;

use Illuminate\Database\Eloquent\Model;

class ProgramLevel extends Model
{
    protected $table = 'loyality_program_levels';
    protected $fillable = ['points', 'loyality_program_id',];
}
