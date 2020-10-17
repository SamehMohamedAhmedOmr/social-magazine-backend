<?php

namespace Modules\Basic\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\Basic\Entities\Gender;
use Modules\Basic\Facade\BasicCache;

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

        CacheHelper::forgetCache(BasicCache::genders());

        foreach ($genders as $gender){
            Gender::updateOrCreate([
                'key' => $gender['key'],
                'name' => $gender['name']
            ]);
        }
    }
}
