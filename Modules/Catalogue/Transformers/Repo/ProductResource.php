<?php

namespace Modules\Catalogue\Transformers\Repo;

use Illuminate\Http\Request;
use Modules\Catalogue\Repositories\CMS\ProductRepository;
use Modules\Settings\Repositories\LanguageRepository;

class ProductResource
{
    private $product_repo;
    private $language_repo;

    public function __construct(LanguageRepository $language_repo, ProductRepository $product_repo)
    {
        $this->product_repo = $product_repo;
        $this->language_repo = $language_repo;
    }

    public function toArray(Request $request)
    {
        $iso = $this->language_repo->pluckISOId();
        $data = $this->prepareObject($request, $iso);

        if ($request->has('variations') && is_array($request->variations)) {
            $data['variations'] = [];
            $i = 0;
            foreach ($request->variations as $variation) {
                $request->replace($variation);
                $data['variations'][$i] = $this->prepareObject($request, $iso);
                if (isset($variation['id'])) {
                    $data['variations'][$i]['id'] = $variation['id'];
                }
                $i++;
            }
        }
        return $data;
    }

    private function prepareObject($object, $iso)
    {
        $data = [];
        if (isset($object->names)) {
            $data = $this->prepareLanguages($iso, $object->names, $object->descriptions);
        }
        if (isset($object->is_active)) {
            $data['is_active'] = (boolean)$object->is_active;
        }
        if (isset($object->is_bundle)) {
            $data['is_bundle'] = (boolean)$object->is_bundle;
        }
        if (isset($object->is_topping)) {
            $data['is_topping'] = (boolean)$object->is_topping;
        }
        if (isset($object->is_sell_with_availability)) {
            $data['is_sell_with_availability'] = (boolean)$object->is_sell_with_availability;
        }
        if (isset($object->max_quantity_per_order)) {
            $data['max_quantity_per_order'] = $object->max_quantity_per_order;
        }
        if (isset($object->sku)) {
            $data['sku'] = $object->sku;
        }
        if ($object->has('weight')) {
            $data['weight'] = $object->weight;
        }
        if ($object->has('parent_id')) {
            $data['parent_id'] = $object->parent_id;
        }
        if ($object->has('unit_of_measure_id')) {
            $data['unit_of_measure_id'] = $object->unit_of_measure_id;
        }
        if ($object->has('brand_id')) {
            $data['brand_id'] = $object->brand_id;
        }
        if ($object->has('main_category_id')) {
            $data['main_category_id'] = $object->main_category_id;
        }
        if ($object->has('topping_menu_id')) {
            $data['topping_menu_id'] = $object->topping_menu_id;
        }
        if ($object->has('categories') && is_array($object->categories)) {
            $data['categories'] = $object->categories;
        }
        if ($object->has('price_lists') && is_array($object->price_lists)) {
            $data['price_lists'] = $object->price_lists;
        }
        if ($object->has('warehouses') && is_array($object->warehouses)) {
            $data['warehouses'] = $object->warehouses;
        }
        if ($object->has('variant_values') && is_array($object->variant_values)) {
            $data['variant_values'] = $object->variant_values;
        }
        if ($object->has('tags') && is_array($object->tags)) {
            $data['tags'] = $object->tags;
        }

        return $data;
    }

    private function prepareLanguages($iso_ids, $names, $descriptions = [], $product_id = null)
    {
        $data = [];
        $names = array_values(request('names'));
        $descriptions = request('descriptions') ? array_values(request('descriptions')) : [];
        $product_languages = [];

        foreach ($names as $name) {
            $description_search = $descriptions !== null && !empty($descriptions)?
                array_search($name['language'], array_column($descriptions, 'language')) : null;
            $description = $description_search !== null ? $descriptions[$description_search]['description'] : null;

            $product_languages [] = [
                'language_id' => $iso_ids[$name['language']],
                'name' => $name['name'],
                'description' => $description,
            ];
        }
        if ($product_languages != []) {
            $data['product_languages'] = $product_languages;
        }
        return $data;
    }
}
