<?php

namespace Modules\Basic\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Basic\Entities\EducationalDegree;

class EducationalDegreesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $degrees = collect([
            [
                'key' => 'PROFESSOR',
                'name' => 'أستاذ'
            ],
            [
                'key' => 'CO-PROFESSOR',
                'name' => 'أستاذ مشارك'
            ],
            [
                'key' => 'ASSISTANT-PROFESSOR',
                'name' => 'أستاذ مساعد'
            ],
            [
                'key' => 'OTHERS',
                'name' => 'غير ذلك'
            ],
        ]);

        foreach ($degrees as $degree){
            EducationalDegree::updateOrCreate([
                'key' => $degree['key'],
                'name' => $degree['name']
            ]);
        }
    }
}
