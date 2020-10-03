<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\TaxesListAmountType;

class TaxesAmountTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amountTypes = ['Fixed','Percentage'];
        foreach ($amountTypes as $amountType) {
            TaxesListAmountType::updateOrCreate([
                'name' => $amountType,
                'key' => strtoupper($amountType)
            ]);
        }
    }
}
