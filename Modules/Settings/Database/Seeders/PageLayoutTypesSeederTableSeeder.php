<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Entities\Layouts\PageLayout;
use Modules\Settings\Entities\Layouts\PageLayoutTypes;

class PageLayoutTypesSeederTableSeeder extends Seeder
{

    public function layouts()
    {
        return collect([
            [
                'type' => 'home_layout',
                'attributes' => [
                    'slider',
                    'collection',
                    'categories',
                ]
            ],
            [
                'type' => 'products_layout',
                'attributes' => [
                    'list_type',
                ]
            ],
            [
                'type' => 'product_card_layout',
                'attributes' => [
                    'show_price',
                    'show_add_to_cart',
                    'show_favourite',
                ]
            ],
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $layouts = $this->layouts();

        $pageLayoutTypes = PageLayoutTypes::with('PagesLayout')->get();

        $this->seed($pageLayoutTypes, $layouts);
    }

    protected function seed($pageLayoutTypes, $layouts)
    {
        foreach ($layouts as $layout) {
            $target_layout = $pageLayoutTypes->where('key', $layout['type'])->first();
            if ($target_layout) {
                $this->update($target_layout, $layout);

            } else { // insert for first time
                $this->insert($layout);
            }
        }
    }

    protected function update($target_layout, $layout){
        foreach ($layout['attributes'] as $attribute) {
            $name = ucwords(str_replace('_', ' ', $attribute));

            PageLayout::updateOrCreate([
                'layout_type_id' => $target_layout->id,
                'name' => $name,
                'key' => $attribute,
            ],[]);
        }
    }


    protected function insert($layout)
    {
        try {
            \DB::transaction(function () use ($layout) {
                $name = ucwords(str_replace('_', ' ', $layout['type']));

                $pageLayoutTypes = PageLayoutTypes::create([
                    'name' => $name,
                    'key' => $layout['type'],
                ]);

                $attributes = [];

                foreach ($layout['attributes'] as $attribute) {
                    $name = ucwords(str_replace('_', ' ', $attribute));

                    $attributes [] = new PageLayout([
                        'layout_type_id' => $pageLayoutTypes->id,
                        'name' => $name,
                        'key' => $attribute,
                    ]);
                }

                $pageLayoutTypes->PagesLayout()->saveMany($attributes);
            });
        } catch (\Throwable $e) {
        }
    }


}
