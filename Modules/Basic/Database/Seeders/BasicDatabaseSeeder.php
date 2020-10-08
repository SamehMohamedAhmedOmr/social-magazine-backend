<?php

namespace Modules\Basic\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BasicDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(EducationalDegreesTableSeeder::class);
        $this->call(EducationalLevelsTableSeeder::class);
        $this->call(GenderTableSeeder::class);
        $this->call(TitlesTableSeeder::class);
        // $this->call("OthersTableSeeder");
    }
}
