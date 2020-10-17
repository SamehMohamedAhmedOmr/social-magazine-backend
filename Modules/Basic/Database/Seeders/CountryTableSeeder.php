<?php

namespace Modules\Basic\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Base\Facade\CacheHelper;
use Modules\Basic\Entities\Country;
use Modules\Basic\Facade\BasicCache;

class CountryTableSeeder extends Seeder
{
    private $file_name = 'countries.json';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path() . "/json/". $this->file_name;
        $countries = json_decode(file_get_contents($path), true);

        CacheHelper::forgetCache(BasicCache::country());

        foreach ($countries as $country) {
            Country::updateOrCreate([
                'country_code' => $country['code'],
                'name' => $country['name']['ar'],
            ], [
                'image' => $country['code']. '.png',
            ]);

        }
    }
}
