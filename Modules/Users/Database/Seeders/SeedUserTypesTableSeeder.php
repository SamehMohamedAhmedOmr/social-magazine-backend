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
            collect([
                'key' => 'MAGAZINE_EDITOR',
                'name' => 'رئيس التحرير'
            ]),
            collect([
                'key' => 'JOURNAL_EDITOR_DIRECTOR',
                'name' => 'مدير التحرير'
            ]),
            collect([
                'key' => 'REFEREES',
                'name' => 'محكم'
            ]),
            collect([
                'key' => 'RESEARCHER',
                'name' => 'باحث'
            ]),
        ]);


        foreach ($types as $type) {
            UserTypes::updateOrCreate([
                'key' => $type->key,
                'name' => $type->name
            ]);
        }

    }
}
