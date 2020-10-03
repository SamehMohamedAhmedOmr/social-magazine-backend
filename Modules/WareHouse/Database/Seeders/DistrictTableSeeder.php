<?php

namespace Modules\WareHouse\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Entities\Language;
use Modules\Settings\Entities\ShippingRules;
use Modules\WareHouse\Entities\Country;
use Modules\WareHouse\Entities\District;
use Modules\WareHouse\Entities\DistrictLanguage;

class DistrictTableSeeder extends Seeder
{
    private $file_name = 'countries_cities.json';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file_name = 'countries_cities.json';
        $path = storage_path() . "/json/" . $file_name;
        $countries = json_decode(file_get_contents($path), true);

        $default_shipping_rule = ShippingRules::find(1);

        $stored_countries = Country::all();

        $stored_districts = DistrictLanguage::all();

        $languages = Language::all();

        $english = $languages->where('iso', 'en')->first();
        $arabic = $languages->where('iso', 'ar')->first();

        foreach ($countries as $country) {
            $target_country = $stored_countries->where('country_code', $country['code'])->first();
            if ($target_country) {
                foreach ($country['states'] as $state) {

                    $district_lang = $stored_districts->where('name', $state['en'])
                        ->where('language_id', $english->id)
                        ->first();

                    if (!$district_lang){
                        $district = District::create([
                            'country_id' => $target_country->id,
                            'shipping_role_id' => ($default_shipping_rule) ? $default_shipping_rule->id : null,
                            'parent_id' => null,
                            'is_active' => 1
                        ]);

                        $district->language()->detach();
                        $district->language()->attach([
                            [
                                'district_id' => $district->id,
                                'language_id' => $english->id,
                                'name' => $state['en'],
                                'description' =>  $state['en']. ' City',
                            ],
                            [
                                'district_id' => $district->id,
                                'language_id' => $arabic->id,
                                'name' => $state['ar'],
                                'description' => 'مدينة ' . $state['ar'] ,
                            ]
                        ]);
                    }

                }
            }
        }
    }
}
