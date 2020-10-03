<?php

namespace Modules\WareHouse\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\ShippingRules;

class ShippingRuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_name = 'Default Shipping Rule';
        ShippingRules::withoutGlobalScopes()->updateOrCreate(
            [
                'key' => strtoupper($default_name),
            ],
            [
            'shipping_rule_label' => $default_name,
            'price' => 0,
            'is_active' => 1
        ]
        );
    }
}
