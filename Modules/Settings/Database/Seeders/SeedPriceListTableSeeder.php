<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Settings\Entities\Currency;
use Modules\Settings\Entities\PriceList;
use Modules\Settings\Entities\PriceListType;
use Modules\WareHouse\Entities\Country;

class SeedPriceListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $price_list_types = PriceListType::all();
        $currency = Currency::withoutGlobalScopes()->where('key', 'EGP')->first();
        $country = Country::withoutGlobalScopes()->where('country_code', 'EG')->first();

        foreach ($price_list_types as $price_list_type) {
            $price_list_name = $price_list_type->name.'_Price';
            PriceList::withoutGlobalScopes()->updateOrCreate([
                'price_list_name' => $price_list_name,
                'type' => $price_list_type->id,
                'is_special' => 0,
                'key' => Str::upper($price_list_name),
                'currency_code' => $currency->id,
                'country_id' => ($country) ? $country->id : null
            ]);
        }
    }
}
