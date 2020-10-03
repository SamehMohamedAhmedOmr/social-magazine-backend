<?php

namespace Modules\Catalogue\Repositories\Frontend;

use Modules\Catalogue\Repositories\Common\ProductCommonRepository;

class ProductRepository extends ProductCommonRepository
{
    public function pagination(
        $per_page = 15,
        $conditions = [],
        $search = null,
        $sort_by = 'id',
        $order_by = 'desc',
        $sort_language = ''
    )
    {
        /**
         *  Joining
         */
        $query = $this->model
            ->join('product_language', 'products.id', 'product_language.product_id')
            ->leftjoin('product_category', 'products.id', 'product_category.product_id')
            ->leftjoin('categories', function ($join) use ($sort_language) {
                $join->on('categories.id', 'products.main_category_id')
                    ->where('categories.is_active', true)->where('categories.deleted_at', null);
                $join->orOn('categories.id', 'product_category.category_id')
                    ->where('categories.is_active', true)->where('categories.deleted_at', null);
            })
            ->join('category_language', 'categories.id', 'category_language.category_id')
            ->where('category_language.language_id', $sort_language)
            ->leftjoin('brands', 'products.brand_id', 'brands.id')->where('brands.deleted_at', null)
            ->join('brand_language', 'brands.id', 'brand_language.brand_id')
            ->where('brand_language.language_id', $sort_language)
            ->join('product_price_list', 'products.id', 'product_price_list.product_id')
            ->join('price_lists', 'product_price_list.price_list_id', 'price_lists.id')
            ->where('price_lists.key', 'STANDARD_SELLING_PRICE');

        /**
         * If sent
         */

        $query = $search && $search['variant_values'] !== null && $search['variant_values'] !== []
            ? $query->leftjoin('product_variant_value', 'products.id', 'product_variant_value.product_id')
            : $query;

        $query = $search && $search['collections'] !== null && $search['collections'] !== []
            ? $query->leftjoin('collection_products', 'products.id', 'collection_products.product_id')
            : $query;


        /**
         * Filtering Part
         */

        ## Boolean Filter
        $query = $query->where('products.is_active', true)->where('product_language.language_id', $sort_language);

        $query = $search && ($search['is_variant'] !== null ||
            ($search['parent_products'] !== null && $search['parent_products'] !== []))
            ? (
            $search['is_variant'] == true ||
            ($search['parent_products'] !== null && $search['parent_products'] !== []) ?
                $query->where('products.parent_id', '!=', null)
                : $query->where('products.parent_id', null)
            ) : $query->where('products.parent_id', null);

        $query = $search && $search['is_topping'] != null
            ? $query->where('products.is_topping', $search['is_topping'])
            : $query;

        $query = $search && $search['is_bundle'] !== null
            ? $query->where('products.is_bundle', $search['is_bundle'])
            : $query;


        ## Relation Filter
        $query = $search && $search['parent_products'] !== null && $search['parent_products'] !== []
            ? $query->where(function ($q) use ($search) {
                $q->whereIn('products.parent_id', array_map('intval', $search['parent_products']))
                    ->orWhereIn('product_language.slug', $search['parent_products']);
            })
            : $query;

        $query = $search && $search['categories'] !== null && $search['categories'] !== []
            ? $query->where(function ($q) use ($search) {
                $q->whereIn('products.main_category_id', array_map('intval', $search['categories']))
                    ->orWhereIn('category_language.slug', $search['categories']);
            })
            : $query;

        $query = $search && $search['brands'] !== null && $search['brands'] !== []
            ? $query->where(function ($q) use ($search) {
                $q->whereIn('products.brand_id', array_map('intval', $search['brands']))
                    ->orWhereIn('brand_language.slug', $search['brands']);
            })
            : $query;

        $query = $search && $search['variant_values'] !== null && $search['variant_values'] !== []
            ? $query->whereIn('product_variant_value.variant_value_id', $search['variant_values'])
            : $query;

        $query = $search && $search['collections'] !== null && $search['collections'] !== []
            ? $query->whereIn('collection_products.collection_id', $search['collections'])
            : $query;


        $query = $search && $search['min_price'] !== null
            ? $query->where('product_price_list.price', '>=', $search['min_price'])
            : $query;

        $query = $search && $search['max_price'] !== null
            ? $query->where('product_price_list.price', '<=', $search['max_price'])
            : $query;

        /**
         * Searching Part
         */
        $query = $search && $search['search_key'] !== null
            ? $query->where('product_language.name', 'LIKE', "%{$search['search_key']}%")
                ->orWhere('product_language.description', 'LIKE', "%{$search['search_key']}%")
                ->orWhere('category_language.name', 'LIKE', "%{$search['search_key']}%")
                ->orWhere('brand_language.name', 'LIKE', "%{$search['search_key']}%")
            : $query;

        $query = $query->has('price');

        $query = $sort_by != 'name'
            ? $query->orderBy("products.$sort_by", $order_by)
            : $query->orderBy('product_language.name', $order_by);

        /**
         * Selecting Needed Data
         */
        $query = $query->select(
            'products.*',
            'product_language.name',
            'product_language.slug',
            'product_language.description'
        );

        return $query->distinct('products.id')->paginate($per_page);
    }

    public function loadRelations($object, $relations = [])
    {
        return $relations != [] ? $object->load($relations) : $object->load(
            [
                'currentLanguage',
                'variantTo.currentLanguage',
                'images',
                'warehouses',
                'favorites',
                'priceLists',
                'price',
                'brand.currentLanguage',
                'mainCategory.currentLanguage',
                'unitOfMeasure.currentLanguage',
                'variantValues' => function ($query) {
                    $query->where('is_active', true);
                },
                'variantValues.variant' => function ($query) {
                    $query->where('is_active', true);
                },
                'variantValues.currentLanguage',
                'variantValues.variant.currentLanguage',
            ]
        );
    }

    public function get($value, $conditions = [], $column = 'slug', $with = [])
    {
        $id = (integer)$value;

        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        if (!$id) {
            $data = $data->whereHas('languages', function ($query) use ($value) {
                $query->where('slug', '=', $value);
            });
        } else {
            $data = $data->where('id', $id)
                ->where('is_active', true);
        }

        return $data
            ->whereHas('mainCategory', function ($query) {
                $query->where('is_active', true);
            })
            ->firstOrFail()->load([
                'currentLanguage',
                'variantTo.currentLanguage',
                'warehouses',
                'favorites',
                'priceLists',
                'price',
                'images',
                'variantValues' => function ($query) {
                    $query->where('is_active', true);
                },
                'variantValues.variant' => function ($query) {
                    $query->where('is_active', true);
                },
                'variantValues.currentLanguage',
                'variantValues.variant.currentLanguage',
                'mainCategory.currentLanguage',
                'categories' => function ($query) {
                    $query->where('is_active', true)->where('deleted_at', null);
                },
                'brand' => function ($query) {
                    $query->where('is_active', true)->where('deleted_at', null);
                },
                'unitOfMeasure' => function ($query) {
                    $query->where('is_active', true)->where('deleted_at', null);
                },

                'toppingMenu' => function ($query) {
                    $query->where('is_active', true)->where('deleted_at', null);
                },
                'toppingMenu.products' => function ($query) {
                    $query->whereHas('mainCategory', function ($query) {
                        $query->where('is_active', true)->where('deleted_at', null);
                    })->whereHas('brand', function ($query) {
                        $query->where('is_active', true)->where('deleted_at', null);
                    })->where('is_active', true)->where('is_topping', true)->where('deleted_at', null);
                },
                'variations' => function ($query) {
                    $query->whereHas('mainCategory', function ($query) {
                        $query->where('is_active', true)->where('deleted_at', null);
                    });
                },
                'variations.currentLanguage',
                'variations.images',
                'variations.mainCategory.currentLanguage',
                'variations.priceLists',
                'variations.price',
                'variations.categories' => function ($query) {
                    $query->where('is_active', true)->where('deleted_at', null);
                },
                'variations.brand' => function ($query) {
                    $query->where('is_active', true)->where('deleted_at', null);
                },
                'variations.unitOfMeasure' => function ($query) {
                    $query->where('is_active', true)->where('deleted_at', null);
                },
                'variations.toppingMenu' => function ($query) {
                    $query->where('is_active', true)->where('deleted_at', null);
                },
                'variations.toppingMenu.products' => function ($query) {
                    $query->whereHas('mainCategory', function ($query) {
                        $query->where('is_active', true)->where('deleted_at', null);
                    })->whereHas('brand', function ($query) {
                        $query->where('is_active', true)->where('deleted_at', null);
                    })->where('is_active', true)->where('is_topping', true)->where('deleted_at', null);
                },
                'variations.variantValues' => function ($query) {
                    $query->where('is_active', true);
                },
                'variations.variantValues.variant' => function ($query) {
                    $query->where('is_active', true);
                },
                'variations.variantValues.currentLanguage',
                'variations.variantValues.variant.currentLanguage',
            ]);
    }

    public function searchSettings()
    {
        $data = [];
        $products = $this->model->where('is_active', true)
            ->whereHas('mainCategory', function ($query) {
                $query->where('is_active', true)->where('deleted_at', null);
            })->distinct()->pluck('id')->toArray();

        $price_list = $this->price_list_model->where('is_active', true)
            ->where(function ($query) {
                $query->where('is_special', true)->orWhere(
                    'key',
                    env('DEFAULT_PRICE_LIST', 'STANDARD_SELLING_PRICE')
                );
            })->first();

        $min_price = $price_list
            ? $this->product_price_list_model
                ->where('price_list_id', $price_list->id)->whereIn('product_id', $products)
                ->orderBy('price', 'ASC')->first()
            : 0;
        $max_price = $price_list
            ? $this->product_price_list_model
                ->where('price_list_id', $price_list->id)->whereIn('product_id', $products)
                ->orderBy('price', 'DESC')->first()
            : 0;


        $data['min_price'] = $min_price ? $min_price->price : 0;
        $data['max_price'] = $max_price ? $max_price->price : 0;
        $data['product_ids'] = $products;

        return $data;
    }
}
