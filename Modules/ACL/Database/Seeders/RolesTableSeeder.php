<?php

namespace Modules\ACL\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ACL\Entities\Permissions;
use Modules\ACL\Entities\Role;
use Modules\Base\Facade\AllowablePermissions;

class RolesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_name = 'Super Role';
        $key = str_replace(' ', '_', strtoupper($role_name));

        $roles = Role::updateOrCreate([
            'name' => $role_name,
            'key' => $key,
        ]);

        $permission = Permissions::all()->pluck('id');

        $roles->syncPermissions($permission);
    }
}
