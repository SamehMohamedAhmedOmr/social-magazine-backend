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
            ['name' => 'Products', 'folder' => 'products'],
            ['name' => 'Brands', 'folder' => 'brands'],
            ['name' => 'Categories', 'folder' => 'categories'],
            ['name' => 'Banners', 'folder' => 'banners'],
            ['name' => 'Companies', 'folder' => 'companies'],
            ['name' => 'Toppings', 'folder' => 'toppings'],
            ['name' => 'Frontend_Settings', 'folder' => 'Frontend_Settings'],
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
