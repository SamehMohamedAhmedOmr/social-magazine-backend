<?php

namespace Modules\Notifications\Entities;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $table = 'device_tokens';
    protected $fillable = ['device_id','device_token','device_os','app_version','user_id'];
}
