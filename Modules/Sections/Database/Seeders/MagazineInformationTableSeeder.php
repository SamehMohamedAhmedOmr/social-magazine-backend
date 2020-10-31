<?php

namespace Modules\Sections\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Base\Facade\CacheHelper;
use Modules\Sections\Entities\MagazineInformation;
use Modules\Sections\Facade\SectionsCache;

class MagazineInformationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        MagazineInformation::updateOrCreate(['title' => config('app.application_name'),], []);

        CacheHelper::forgetCache(SectionsCache::magazineInformation());
    }
}
