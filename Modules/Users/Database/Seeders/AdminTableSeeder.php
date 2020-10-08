<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ACL\Entities\Role;
use Modules\Basic\Entities\Gender;
use Modules\Users\Entities\User;
use Modules\Users\Entities\UserTypes;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_types = UserTypes::where('key','MAGAZINE_EDITOR_MANAGER')
            ->orWhere('key','RESEARCHER')->pluck('id');
        $gender = Gender::where('key','MALE')->first();

        $user = User::updateOrCreate([
            'email' => 'admin@magzine.com',
        ], [
            'first_name' => 'Admin',
            'family_name' => 'of Magazine',
            'password' => bcrypt('123456789'),
            'gender_id' => $gender->id,
        ]);

        $user->accountTypes()->detach();
        $user->accountTypes()->attach($user_types);


//        $role = Role::where('key', 'SUPER_ROLE')->first();
//        $user->roles()->detach();
//        $user->roles()->attach($role->id);
    }
}
