<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ACL\Entities\Role;
use Modules\Users\Entities\User;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate([
            'email' => 'admin@magzine.com',
        ], [
            'name' => 'CMSUser',
            'password' => bcrypt('123456789'),
            'user_type' => 1
        ]);


        $role = Role::where('key', 'SUPER_ROLE')->first();
        $user->roles()->detach();
        $user->roles()->attach($role->id);
    }
}
