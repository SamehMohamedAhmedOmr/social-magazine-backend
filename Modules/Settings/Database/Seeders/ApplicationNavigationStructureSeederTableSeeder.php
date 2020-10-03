<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\FrontendSettings\ApplicationNavigationStructure;

class ApplicationNavigationStructureSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $application_navigation_structures = collect([
            'TABS' , 'MENU' , 'TABS_AND_MENU'
        ]);

        foreach ($application_navigation_structures as $structure){
            $name = ucwords(str_replace('_', ' ', $structure));

            ApplicationNavigationStructure::updateOrCreate([
                'name' => $name,
                'key' => $structure
            ],[]);
        }

    }

}
