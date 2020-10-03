<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ACL\Entities\Role;
use Modules\Configuration\Facades\TenantHelper;
use Modules\Users\Entities\Admin;
use Modules\Users\Entities\User;
use Modules\WareHouse\Entities\Country;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_information = \Session::get('project_info');

        $user = User::updateOrCreate([
            'email' => $project_information['admin_email'],
        ], [
            'name' => 'Admin',
            'password' => $project_information['admin_password'],
            'user_type' => 1
        ]);

        $admin = Admin::updateOrCreate([
            'user_id' => $user->id
        ]);


        $countries = Country::all();
        $countries_id = $countries->pluck('id')->toArray();

        $admin->countries()->detach();
        $admin->countries()->attach($countries_id);


        $role = Role::where('key', 'SUPER_ROLE')->first();
        $user->roles()->detach();
        $user->roles()->attach($role->id);
    }
}
