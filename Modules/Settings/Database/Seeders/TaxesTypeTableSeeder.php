<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\TaxesListAmountType;
use Modules\Settings\Entities\TaxesListType;

class TaxesTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amountTypes = collect(['On Net Total','On Total after discount','On total after shipping']);
        foreach ($amountTypes as $amountType) {
            $key = preg_replace('/\s+/', '_', $amountType);
            TaxesListType::updateOrCreate([
                'name' => $amountType,
                'key' => strtoupper($key)
            ]);
        }
    }
}
