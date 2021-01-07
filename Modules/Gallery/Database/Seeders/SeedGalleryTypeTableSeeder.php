<?php

namespace Modules\Gallery\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Gallery\Entities\GalleryType;

class SeedGalleryTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = collect([
            ['name' => 'Users', 'folder' => 'users'],
            ['name' => 'Magazine_Category', 'folder' => 'magazine_category'], // التصنيف
            ['name' => 'testimonials', 'folder' => 'testimonials'],
            ['name' => 'news', 'folder' => 'news'],
            ['name' => 'events', 'folder' => 'events'],
            ['name' => 'photos', 'folder' => 'photos'],
            ['name' => 'activities', 'folder' => 'activities'],
        ]);

        foreach ($types as $type) {
            GalleryType::updateOrCreate([
                'name' => $type['name'],
                'key' => strtoupper($type['name']),
                'folder' => $type['folder'],
            ]);
        }
    }
}
