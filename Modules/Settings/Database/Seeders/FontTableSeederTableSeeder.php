<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Entities\Fonts;

class FontTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fontsCollections = collect([]);

        $fontsCollections->push([
            'font_name' => 'OpenSans-Regular',
            'font_path' => 'OpenSans-Regular.ttf',
            'type' => 0,
        ]);

        $fontsCollections->push([
            'font_name' => 'OpenSans-Bold',
            'font_path' => 'OpenSans-Bold.ttf',
            'type' => 1,
        ]);

        $fontsCollections->push([
            'font_name' => 'OpenSans-Italic',
            'font_path' => 'OpenSans-Italic.ttf',
            'type' => 2,
        ]);

        $fontsCollections->push([
            'font_name' => 'Arial-Regular',
            'font_path' => 'Arial-Regular.ttf',
            'type' => 0,
        ]);

        $fontsCollections->push([
            'font_name' => 'Calibri-Regular',
            'font_path' => 'Calibri-Regular.ttf',
            'type' => 0,
        ]);

        $fontsCollections->push([
            'font_name' => 'Times-New-Roman-Bold',
            'font_path' => 'Times-New-Roman-Bold.ttf',
            'type' => 1,
        ]);

        $fontsCollections->push([
            'font_name' => 'Times-New-Roman-Italic',
            'font_path' => 'Times-New-Roman-Italic.ttf',
            'type' => 2,
        ]);

        $fontsCollections->push([
            'font_name' => 'Times-New-Roman-Regular',
            'font_path' => 'Times-New-Roman-Regular.ttf',
            'type' => 0,
        ]);

        $fonts = $fontsCollections->toArray();

        foreach ($fonts as $font){
            Fonts::updateOrCreate($font,[]);
        }
    }
}
