<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\PriceListType;

class SeedPriceListTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $basic_types = ['Standard_Selling','Standard_Buying','Autoship_Selling','Autoship_Buying'];
        foreach ($basic_types as $basic_type) {
            PriceListType::updateOrCreate([
                'name' => $basic_type
            ]);
        }
    }
}
