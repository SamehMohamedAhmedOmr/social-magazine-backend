<?php

namespace Modules\ACL\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleHasPermissions extends Pivot
{
    protected $table = 'role_permissions';
    protected $fillable = ['permission_id', 'role_id'];
}
