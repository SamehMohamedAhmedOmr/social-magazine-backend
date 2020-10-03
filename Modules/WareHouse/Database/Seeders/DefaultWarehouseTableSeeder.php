<?php

namespace Modules\WareHouse\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Entities\Language;
use Modules\WareHouse\Entities\Country;
use Modules\WareHouse\Entities\PaymentEntryType;
use Modules\WareHouse\Entities\Warehouse;

class DefaultWarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Country::where('country_code', config('base.default_country'))->first();

        $languages = Language::all();

        $english = $languages->where('iso', 'en')->first();
        $arabic = $languages->where('iso', 'ar')->first();

        $warehouse_code = 'DEFAULT';

        $warehouse = Warehouse::withoutGlobalScopes()->where('warehouse_code', $warehouse_code)->first();

        if (!$warehouse){
            $warehouse = Warehouse::create([
                'warehouse_code' => $warehouse_code,
                'default_warehouse' => 1,
                'country_id' => ($country) ? $country->id : null
            ]);
        }



        $languages = [
            [
                'language_id' => $english->id,
                'name' => 'Default Warehouse',
                'description' => 'Default Warehouse Description'
            ],
            [
                'language_id' => $arabic->id,
                'name' => 'المخزن الرئيسي',
                'description' => 'وصف المخزن الرئيسي'
            ]
        ];

        $warehouse->language()->detach();
        $warehouse->language()->attach($languages);

    }
}
