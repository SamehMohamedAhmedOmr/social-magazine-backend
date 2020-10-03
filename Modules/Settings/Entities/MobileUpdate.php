<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobileUpdate extends Model
{
    use SoftDeletes;
    protected $table = 'mobile_updates';
    protected $fillable = [
        'device_type','application_version','build_number',
        'is_active','force_update','release_date'
    ];
}
