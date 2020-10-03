<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\ACL\Entities\Role;
use Modules\ACL\Entities\UserHasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_type', 'is_active', 'token_last_renew'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function researcher()
    {
        return $this->hasOne(Researcher::class, 'user_id', 'id');
    }

    public function cmsUser()
    {
        return $this->hasOne(CMSUser::class, 'user_id', 'id');
    }

    public function address()
    {
        return $this->hasMany(Address::class, 'user_id', 'id');
    }


    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'user_roles',
            'user_id',
            'role_id'
        )
            ->using(UserHasRoles::class);
    }


}
