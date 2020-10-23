<?php

namespace Modules\Sections\Entities;

use Illuminate\Database\Eloquent\Model;

class Tracker extends Model
{
    protected $fillable = [ 'ip', 'date' ];
    protected $table = 'trackers';

    public function hit() {
        return static::firstOrCreate([
            'ip'   => $_SERVER['REMOTE_ADDR'],
            'date' => date('Y-m-d'),
        ])->save();
    }
}
