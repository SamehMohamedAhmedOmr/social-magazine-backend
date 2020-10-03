<?php

namespace Modules\ACL\Entities;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['name', 'route_name', 'key'];


    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'permission_id',
            'role_id'
        )
            ->using(RoleHasPermissions::class);
    }
}
