<?php

namespace Modules\WareHouse\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Entities\Language;
use Modules\WareHouse\Entities\Country;

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

        $languages = Language::all();
        $english = $languages->where('iso','en')->first();
        $arabic = $languages->where('iso','ar')->first();

        foreach ($countries as $country) {
            $country_object =  Country::updateOrCreate([
                'country_code' => $country['code'],
            ], [
                'image' => $country['code']. '.png',
            ]);

            $country_object->language()->detach();

            $country_object->language()->attach([
                [
                    'country_id' => $country_object->id,
                    'language_id' => $english->id,
                    'name' => $country['name']['en'],
                    'description' => $country['name']['en'] . ' Country'
                ],
                [
                    'country_id' => $country_object->id,
                    'language_id' => $arabic->id,
                    'name' => $country['name']['ar'],
                    'description' => 'دولة  ' . $country['name']['ar']
                ]
            ]);
        }
    }
}
