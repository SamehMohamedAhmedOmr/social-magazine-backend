<?php

namespace Modules\WareHouse\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class WareHouseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(PaymentEntryTypeTableSeeder::class);
        $this->call(ShippingRuleTableSeeder::class);

        $this->call(DistrictTableSeeder::class);

        $this->call(DefaultWarehouseTableSeeder::class);
        $this->call(OrderStatusTableSeeder::class);

        // FOR TEST
      //  $this->call(OrderSeederTableSeeder::class);
    }
}
