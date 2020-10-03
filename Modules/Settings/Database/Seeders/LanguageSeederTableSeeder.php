<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Language;

class LanguageSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $supported_languages = config('base.supported_languages');

        foreach ($supported_languages as $language => $language_name) {
            Language::updateOrCreate([
                'iso' => $language
            ], [
                'name' => $language_name
            ]);
        }
    }
}
