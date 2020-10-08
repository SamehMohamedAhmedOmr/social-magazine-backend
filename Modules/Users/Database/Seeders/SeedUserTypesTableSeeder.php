<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
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
        $types = collect([
            [
                'key' => 'MAGAZINE_EDITOR_MANAGER',
                'name' => 'رئيس التحرير'
            ],
            [
                'key' => 'JOURNAL_EDITOR_DIRECTOR',
                'name' => 'مدير التحرير'
            ],
            [
                'key' => 'REFEREES',
                'name' => 'محكم'
            ],
            [
                'key' => 'RESEARCHER',
                'name' => 'باحث'
            ],
        ]);


        foreach ($types as $type) {
            UserTypes::updateOrCreate([
                'key' => $type['key'],
                'name' => $type['name']
            ]);
        }

    }
}
