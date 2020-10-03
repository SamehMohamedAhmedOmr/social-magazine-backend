<?php

namespace Modules\ACL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Users\Entities\User;

class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';
    protected $fillable = ['name', 'key'];


    public function permissions()
    {
        return $this->belongsToMany(
            Permissions::class,
            'role_permissions',
            'role_id',
            'permission_id'
        )
            ->using(RoleHasPermissions::class);
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_roles',
            'role_id',
            'user_id'
        )
            ->using(UserHasRoles::class);
    }

    public function syncPermissions($permissions)
    {
        $this->permissions()->detach();
        $this->permissions()->attach($permissions);
    }
}
