<?php

namespace Modules\Basic\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Basic\Entities\EducationalLevel;

class EducationalLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = collect([
            [
                'key' => 'PHD',
                'name' => 'دكتوراه'
            ],
            [
                'key' => 'MASTER',
                'name' => 'ماجستير'
            ],
            [
                'key' => 'BACHELOR',
                'name' => 'بكالوريوس'
            ],
            [
                'key' => 'LIC',
                'name' => 'ليسانس'
            ],
        ]);

        foreach ($levels as $level){
            EducationalLevel::updateOrCreate([
                'key' => $level['key'],
                'name' => $level['name']
            ]);
        }
    }
}
