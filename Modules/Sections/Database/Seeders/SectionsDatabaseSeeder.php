<?php

namespace Modules\Sections\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SectionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(MagazineInformationTableSeeder::class);
        // $this->call("OthersTableSeeder");
    }
}
