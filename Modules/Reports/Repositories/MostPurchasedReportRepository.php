<?php

namespace Modules\Reports\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Product;

class MostPurchasedReportRepository extends LaravelRepositoryClass
{
    public $productsModel;

    public function __construct(Product $productsModel)
    {
        $this->productsModel = $productsModel;
    }

    public function index($user_id, $per_page = 15, $sort_by = 'id', $order_by = 'desc')
    {
        return $this->productsModel
            ->join('product_language', 'products.id', '=', 'product_language.product_id')
            ->join('order_items', 'products.id', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', 'orders.id')
            ->where('orders.is_restored', false)
            ->where('orders.deleted_at', null)
            ->where('user_id', $user_id)
            ->select(
                'products.*',
                'product_language.name as name',
                'product_language.slug as slug',
                'product_language.description as description',
                \DB::raw('SUM(order_items.quantity) as total_quantity'),
                \DB::raw('SUM(order_items.quantity * order_items.price) as total_price')
            )->groupBy('products.id', 'product_language.name', 'product_language.slug', 'product_language.description')
            ->orderBy($sort_by, $order_by)
            ->paginate($per_page);
    }

    public function loadRelations($object, $relations = [])
    {
        return $relations != [] ? $object->load($relations) : $object->load([
            'currentLanguage',
            'warehouses',
            'favorites',
            'priceLists',
            'price',
            'images',
            'variantTo.currentLanguage',
            'brand.currentLanguage',
            'mainCategory.currentLanguage',
            'unitOfMeasure.currentLanguage',
            'variantValues' => function ($query) {
                $query->where('is_active', true);
            },
            'variantValues.variant' => function ($query) {
                $query->where('is_active', true);
            },
            'variantValues.variant.currentLanguage',
        ]);
    }
}
