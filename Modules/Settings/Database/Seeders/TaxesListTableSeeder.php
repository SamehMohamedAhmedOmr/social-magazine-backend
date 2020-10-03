<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\TaxesList;
use Modules\Settings\Entities\TaxesListAmountType;
use Modules\Settings\Entities\TaxesListLanguage;

class TaxesListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tax_name = 'vat';
        $tax = TaxesList::updateOrCreate([
            'price' => 14,
            'tax_type_id' => 3,
            'tax_amount_type_id' => 2,
            'is_active' => 0,
            'key' => strtoupper($tax_name),
            'country_id' => 1,
        ]);

        TaxesListLanguage::updateOrCreate([
            'tax_list_id' => $tax->id,
            'language_id' => 1,
            'name' => $tax_name,
        ]);
    }
}
