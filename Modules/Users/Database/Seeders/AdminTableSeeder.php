<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ACL\Entities\Role;
use Modules\Basic\Entities\Country;
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
            ->orWhere('key','RESEARCHER')->get();
        $gender = Gender::where('key','MALE')->first();

        $country = Country::where('country_code','EG')->first();

        $user = User::updateOrCreate([
            'email' => 'drabosohaib2015@gmail.com',
        ], [
            'first_name' => 'Bassem',
            'family_name' => 'Youssef',
            'password' => bcrypt('01014264334'),
            'gender_id' => $gender->id,
            'country_id' => $country->id
        ]);

        $admin_types = [];

        foreach ($user_types as $type){
            $main_type = ($type->key == 'MAGAZINE_EDITOR_MANAGER' ) ? 1 : 0;

            $admin_types [] = [
                'user_type_id' => $type->id,
                'main_type' => $main_type,
            ];
        }



        $user->accountTypes()->detach();
        $user->accountTypes()->attach($admin_types);


//        $role = Role::where('key', 'SUPER_ROLE')->first();
//        $user->roles()->detach();
//        $user->roles()->attach($role->id);
    }
}
