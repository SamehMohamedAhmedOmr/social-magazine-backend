<?php

namespace Modules\Catalogue\Repositories\CMS;

use Modules\Catalogue\Repositories\Common\ProductCommonRepository;
use \DB;

class ProductRepository extends ProductCommonRepository
{
    public function index($order_by, $sort_by, $sort_language, $search = [])
    {
        $query = $this->query(
            $order_by,
            $sort_by,
            $sort_language,
            $search
        );

        return $query->get();
    }

    public function pagination($per_page, $order_by, $sort_by, $sort_language, $search = [])
    {
        $query = $this->query(
            $order_by,
            $sort_by,
            $sort_language,
            $search
        );

        return $query->paginate($per_page);
    }

    protected function query($order_by, $sort_by, $sort_language, $search)
    {
        /**
         *  Joining
         */
        $query = $this->model->join('product_language', 'products.id', 'product_language.product_id');

        $query = $search['category_ids'] !== null || $search['search_key'] !== null
            ? $query
                ->leftjoin('product_category', 'products.id', 'product_category.product_id')
                ->join('categories', function ($join) {
                    $join->on('categories.id', '=', 'product_category.category_id');
                    $join->orOn('categories.id', '=', 'products.main_category_id');
                })
            : $query;

        $query = $search['search_key'] !== null
            ? $query
                ->leftjoin('brands', 'products.brand_id', 'brands.id')
                ->join('brand_language', 'brands.id', 'brand_language.brand_id')
                ->leftjoin('category_language', 'categories.id', 'category_language.category_id')
            : $query;

        $query = $search['min_price'] !== null || $search['max_price'] !== null
            ? $query
                ->leftjoin('product_price_list', 'products.id', 'product_price_list.product_id')
                ->join('price_lists', 'product_price_list.price_list_id', '=', 'price_lists.id')
                ->where('price_lists.key', 'STANDARD_SELLING_PRICE')
            : $query;

        $query = $search['variant_values'] !== null
            ? $query->leftjoin('product_variant_value', 'products.id', 'product_variant_value.product_id')
            : $query;

        /*$query = $search['warehouses_ids'] !== null
            ? $query->leftjoin('product_warehouses', 'products.id', 'product_warehouses.product_id')
            : $query;*/

        $query = $search['topping_menus'] !== null
            ? $query->leftjoin('product_topping_menu', 'products.id', 'product_topping_menu.product_id')
            : $query;

        /**
         * Searching Part
         */
        $query = $search['search_key'] !== null
            ? $query->where(function ($q) use ($search) {
                $q->withAnyTags([$search['search_key']])
                    ->orwhere('product_language.name', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('product_language.description', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('category_language.name', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('brand_language.name', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('products.id', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('products.sku', 'LIKE', '%' . $search['search_key'] . '%');
            })
            : $query;

        /**
         * Filtering Part
         */

        ## Boolean Filter

        $query = $search['is_active'] !== null
            ? $query->where('products.is_active', $search['is_active'])
            : $query;

        $query = $search['is_sell_with_availability'] !== null
            ? $query->where('products.is_sell_with_availability', $search['is_sell_with_availability'])
            : $query;

        $query = $search['is_variant'] !== null || $search['parent_products'] !== null
            ? (
            $search['is_variant'] == true || $search['parent_products'] !== null ?
                $query->where('products.parent_id', '!=', null)
                : $query->where('products.parent_id', null)
            ) : $query->where('products.parent_id', null);


        $query = $search['is_topping'] !== null
            ? $query->where('products.is_topping', $search['is_topping'])
            : $query;

        $query = $search['is_bundle'] !== null
            ? $query->where('products.is_bundle', $search['is_bundle'])
            : $query;

        $query = $search['trashed'] !== null && $search['trashed'] == true
            ? $query->withTrashed()->where('products.deleted_at', '!=', null)
            : $query;

        ## Relation Filter

        $query = $search['parent_products'] !== null && $search['parent_products'] !== []
            ? $query->whereIn('products.parent_id', $search['parent_products'])
            : $query;

        $query = $search['category_ids'] !== null && $search['category_ids'] !== []
            ? $query->whereIn('categories.id', $search['category_ids'])
            : $query;

        $query = $search['brand_ids'] !== null && $search['brand_ids'] !== []
            ? $query->whereIn('products.brand_id', $search['brand_ids'])
            : $query;

        $query = $search['min_price'] !== null
            ? $query->where('product_price_list.price', '>=', $search['min_price'])
            : $query;

        $query = $search['max_price'] !== null
            ? $query->where('product_price_list.price', '<=', $search['max_price'])
            : $query;

        $query = $search['variant_values'] !== null
            ? $query->whereIn('product_variant_value.variant_id', $search['variant_values'])
            : $query;

        /*$query = $search['warehouses_ids'] !== null
            ? $query->whereIn('product_warehouses.warehouse_id', $search['warehouses_ids'])
            : $query;*/

        $query = $search['topping_menus'] !== null
            ? $query->whereIn('product_topping_menu.topping_menu_id', $search['topping_menus'])
            : $query;


        /**
         *  Ordering Part
         */
        $query = $sort_by == 'name' ? $query->where('product_language.language_id', $sort_language) : $query;

        $query = $sort_by != 'name'
            ? $query->orderBy('products.' . $sort_by, $order_by)
            : $query->orderBy('product_language.name', $order_by);

        /**
         * Selecting Needed Data
         */
        $query = $sort_by == 'name'
            ? $query->select('products.*', 'product_language.name')
            : $query->select('products.*'); // because duplicaton

        return $query->distinct('products.id');
    }

    public function loadRelations($object, $only = [], $additional = [])
    {
        $relations = [
            'languages.language',
            'variations.languages.language',
            'brand.languages.language',
            'variations.brand.languages.language',
            'mainCategory.languages.language',
            'variations.mainCategory.languages.language',
            'categories.languages.language',
            'variations.categories.languages.language',
            'toppingMenu.languages.language',
            'variations.toppingMenu.languages.language',
            'toppingMenu.products.languages.language',
            'variations.toppingMenu.products.languages.language',
            'priceLists',
            'variations.priceLists',
            'warehouses',
            'variations.warehouses',
            'unitOfMeasure.languages.language',
            'variations.unitOfMeasure.languages.language',
            'images',
            'variations.images',
            'variantValues.variant.languages.language',
            'variations.variantValues.variant.languages.language',
            'variantValues.languages.language',
            'variations.variantValues.languages.language',
            'usersSubscription',
        ];

        $relations = $additional !== [] ? array_merge($relations, $additional) : $relations;
        $relations = $only !== [] ? $only : $relations;

        return $object->load($relations);
    }

    #### Get Section ####

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->where($column, $value)->withTrashed()->firstOrFail();

        return $this->loadRelations($data, [], ['tags']);
    }


    #### Create Section ####

    public function create(array $attributes, $load = [])
    {
        return DB::transaction(function () use ($attributes, $load){
            $product_id = $this->createObject($attributes);
            if (array_key_exists('variations', $attributes)) {
                $variations = $attributes['variations'];
                foreach ($variations as $variation) {
                    $this->createObject($variation, $product_id);
                }
            }

            return $this->get($product_id);
        });
    }

    private function createObject($attributes, $parent_id = null)
    {
        $checkSKU = null;
        if (array_key_exists('sku', $attributes)) {
            $checkSKU = $this->checkSKU($attributes['sku']);
            if ($checkSKU) {
                $attributes['sku'] = null;
            }
        } else {
            $attributes['sku'] = null;
        }

        if ($parent_id !== null) {
            $attributes['parent_id'] = $parent_id;
        }

        $data = $this->model->create($attributes);

        if ($attributes['sku'] == null) {
            $sku = '';
            $sku .= str_pad((string)$data->category_id, 5, '0', STR_PAD_LEFT);
            $sku .= str_pad((string)$data->brand_id, 5, '0', STR_PAD_LEFT);
            $sku .= str_pad((string)$data->id, 5, '0', STR_PAD_LEFT);

            $data->update(['sku' => $sku]);
        }

        $name_slug = $attributes['product_languages'];
        foreach ($name_slug as &$item) {
            $item['product_id'] = $data->id;
            $this->product_language_model->create($item);
        }

        $this->sync($data, $attributes);

        return $data->id;
    }

    #### Update Section ####

    public function update($value, $attributes = [], $conditions = [], $column = 'id', $with = [])
    {
        return DB::transaction(function () use ($value, $attributes, $conditions, $column, $with) {
            $name_slug = null;
            if (array_key_exists('product_languages', $attributes)) {
                $name_slug = $attributes['product_languages'];
                unset($attributes['product_languages']);
            }
            if (array_key_exists('variations', $attributes)) {
                $variations = $attributes['variations'];
                foreach ($variations as $variation) {
                    if (array_key_exists('id', $variation)) {
                        $this->update($variation['id'], $variation);
                    } else {
                        $this->createObject($variation, $value);
                    }
                }
                unset($attributes['variations']);
            }

            $data = $conditions != []
                ? $this->model->where($conditions)
                : $this->model;

            $data = $data->where($column, $value)->withTrashed()->firstOrFail();

            if ($attributes != []) {
                $data->update($attributes);
            }

            if ($name_slug !== null) {
                $data->languages()->delete();

                foreach ($name_slug as &$item) {
                    $item['product_id'] = $data->id;
                    $this->product_language_model->create($item);
                }
            }

            $this->sync($data, $attributes);


            return $this->loadRelations($data, [], ['tags']);
        });
    }

    #### Restore & Deletion Section ####

    public function restore($value, $conditions = [], $column = 'id')
    {
        return $this->model->where('id', $value)->onlyTrashed()->firstOrFail()->restore();
    }

    public function delete($value, $conditions = [], $column = 'id')
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;
        $data = $data->where($column, $value)->firstOrFail();


        return $data->delete();
    }

    #### Helper Methods Section ####

    private function sync($product, $attributes)
    {
        if (array_key_exists('categories', $attributes)) {
            $categories = $attributes['categories'] ?? [];
            $product->categories()->sync($categories);
        }
        if (array_key_exists('variant_values', $attributes)) {
            $variant_values = $attributes['variant_values'] ?? [];
            $product->variantValues()->sync($variant_values);
        }

        if (array_key_exists('price_lists', $attributes)) {
            $price_lists = $attributes['price_lists'] ?? [];
            $data = [];
            foreach ($price_lists as $price_list) {
                $data[$price_list['id']] = ['price' => $price_list['price']];
            }
            $product->priceLists()->sync($data);
        }

        if (array_key_exists('warehouses', $attributes)) {
            $warehouses = $attributes['warehouses'] ?? [];
            foreach ($warehouses as $warehouse) {
                if (!array_key_exists('id', $warehouse)) {
                    continue;
                }
                $this->productWarehouseModel->updateOrInsert([
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse['id'],
                ], [
                    'available' => $warehouse['available'] ?? true
                ]);
            }
        }

        if (array_key_exists('tags', $attributes)) {
            $tags = $attributes['tags'] ?? [];
            $product->syncTags($tags);
        }
    }

    public function getBulk($key, $products)
    {
        return $this->model->whereIn($key, $products)->get();
    }

    public function getOne($key, $products)
    {
        return $this->model->where($key, $products)->first();
    }

    private function checkSKU($sku)
    {
        return $this->model->where('sku', $sku)->withTrashed()->first();
    }

    public function getSlugIfDuplication($slug)
    {
        $query = $this->product_language_model->where('slug', $slug);
        return $query->first();
    }

    public function dynamicLinksForProducts($dynamicLinkService)
    {
        $products = $this->model->all();
        $products = $products->load('currentLanguage');

        foreach ($products as $product) {
            $product->update(['dynamic_link' =>
                $dynamicLinkService->createDynamicLink(route('products.show', $product->currentLanguage->slug))]);
        }

        return true;
    }

    public function syncImages($model, $images)
    {
        $model->images()->detach();
        $model->images()->attach($images);
    }
}
