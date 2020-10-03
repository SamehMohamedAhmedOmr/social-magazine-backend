<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\WareHouse\Database\Seeders\CountryTableSeeder;
use Modules\WareHouse\Database\Seeders\PaymentEntryTypeTableSeeder;

class SettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(LanguageSeederTableSeeder::class);
        $this->call(SeedDaysTableSeeder::class);
        $this->call(CountryTableSeeder::class);

        $this->call(CurrencyTableSeeder::class);
        $this->call(SeedPriceListTypeTableSeeder::class);
        $this->call(SeedPriceListTableSeeder::class);
        $this->call(PaymentEntryTypeTableSeeder::class);
        $this->call(TaxesAmountTypeTableSeeder::class);
        $this->call(TaxesTypeTableSeeder::class);
        $this->call(PaymentMethodSeederTableSeeder::class);
        $this->call(TaxesListTableSeeder::class);
        $this->call(MenuNavigationTypeSeederTableSeeder::class);
        $this->call(FontTableSeederTableSeeder::class);
        $this->call(PageLayoutTypesSeederTableSeeder::class);
        $this->call(ApplicationNavigationStructureSeederTableSeeder::class);
    }
}
