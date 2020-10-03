<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class CMSUser extends Model
{
    use SoftDeletes, Notifiable;

    protected $table = 'cms_users';
    protected $fillable = ['user_id'];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'Modules.Users.CMSUser.'.$this->id;
    }
}
