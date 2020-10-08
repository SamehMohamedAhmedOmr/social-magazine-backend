<?php

namespace Modules\Basic\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Basic\Entities\Gender;

class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genders = collect([
            [
                'key' => 'MALE',
                'name' => 'ذكر'
            ],
            [
                'key' => 'FEMALE',
                'name' => 'أنثى'
            ],
        ]);

        foreach ($genders as $gender){
            Gender::updateOrCreate([
                'key' => $gender['key'],
                'name' => $gender['name']
            ]);
        }
    }
}
