<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Settings\Entities\Currency;
use Modules\WareHouse\Entities\Country;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Country::where('country_code', config('base.default_country'))->first();

        $currencies = ['egp'];
        foreach ($currencies as $currency) {
            Currency::withoutGlobalScopes()->updateOrCreate([
                'key' => Str::upper($currency)
            ],
            [
                'name' => $currency,
                'country_id' => $country->id,
            ]);
        }
    }
}
