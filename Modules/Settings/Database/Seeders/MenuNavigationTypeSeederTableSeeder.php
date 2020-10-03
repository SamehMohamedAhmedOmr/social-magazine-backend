<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Entities\FrontendSettings\FrontendMenuNavigationType;

class MenuNavigationTypeSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $navigation_types = collect([
            'Screen', 'Categories', 'Page'
        ]);

        foreach ($navigation_types as $navigation_type) {
            FrontendMenuNavigationType::updateOrCreate([
                'name' => $navigation_type,
                'key' => strtoupper($navigation_type)
            ],[]);
        }
    }
}
