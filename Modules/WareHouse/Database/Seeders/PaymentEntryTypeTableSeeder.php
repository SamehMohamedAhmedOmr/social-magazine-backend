<?php

namespace Modules\WareHouse\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WareHouse\Entities\PaymentEntryType;

class PaymentEntryTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_entry_types = ['Cash','Bank'];
        foreach ($payment_entry_types as $payment_entry_type) {
            PaymentEntryType::updateOrCreate([
                'name' => $payment_entry_type,
                'key' => strtoupper($payment_entry_type),
            ]);
        }
    }
}
