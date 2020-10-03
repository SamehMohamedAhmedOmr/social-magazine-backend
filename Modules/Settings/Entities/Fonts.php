<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;

class Fonts extends Model
{
    protected $table = 'fonts';

    protected $fillable = [
        'font_name',
        'font_path',
        'type'
    ];
}
