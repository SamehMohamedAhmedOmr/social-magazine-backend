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
        'first_name', 'family_name' , 'email', 'email_verified_at' ,'password',
        'alternative_email' , 'token_last_renew',
        'user_type', 'is_active',
        'title_id' , 'educational_level_id', 'educational_degree_id',
        'educational_field' , 'university', 'faculty', 'phone_number',
        'fax_number', 'address'
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
