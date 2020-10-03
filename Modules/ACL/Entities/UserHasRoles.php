<?php

namespace Modules\ACL\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserHasRoles extends Pivot
{
    protected $table = 'user_roles';
    protected $fillable = ['user_id', 'role_id'];
}
