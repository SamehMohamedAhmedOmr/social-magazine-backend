<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\PaymentMethod;
use Modules\WareHouse\Entities\Country;

class PaymentMethodSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Country::where('country_code', config('base.default_country'))->first();
        $payment_method = PaymentMethod::withoutGlobalScopes()->updateOrCreate([
            'key' => 'CASH_ON_DELIVERY',
        ],
        [
            'country_id' => $country->id,
        ]);

        // payment_method seeder
        $payment_method->languages()->detach();
        $payment_method->languages()->attach([
            ['language_id' => 1, 'name' => 'Cash'],
            ['language_id' => 2, 'name' =>  'نقدًا عند الاستلام'],
        ]);


    }
}
