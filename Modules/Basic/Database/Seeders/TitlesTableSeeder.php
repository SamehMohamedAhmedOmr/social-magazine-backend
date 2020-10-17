<?php

namespace Modules\Basic\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\Basic\Entities\Title;
use Modules\Basic\Facade\BasicCache;

class TitlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titles = collect([
            [
                'key' => 'DR',
                'name' => 'دكتور'
            ],
            [
                'key' => 'PROF',
                'name' => 'بروفيسور'
            ],
            [
                'key' => 'MR',
                'name' => 'السيد'
            ],
            [
                'key' => 'MRS',
                'name' => 'السيدة'
            ],
        ]);

        CacheHelper::forgetCache(BasicCache::titles());

        foreach ($titles as $title) {
            Title::updateOrCreate([
                'key' => $title['key'],
                'name' => $title['name']
            ]);
        }
    }
}
