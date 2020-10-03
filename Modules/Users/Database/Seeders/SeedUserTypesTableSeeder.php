<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\UserTypes;

class SeedUserTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Model::unguard();

        UserTypes::updateOrCreate([
            'name' => 'Admin'
        ]);
        UserTypes::updateOrCreate([
            'name' => 'Client'
        ]);
        UserTypes::updateOrCreate([
            'name' => 'Driver'
        ]);
        // $this->call("OthersTableSeeder");
    }
}
