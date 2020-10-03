<?php

namespace Modules\Reports\Repositories;

use Illuminate\Support\Facades\Session;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Product;

class SalesReportRepository extends LaravelRepositoryClass
{
    public $productModel;

    public function __construct(Product $product)
    {
        $this->productModel = $product;
    }

    public function index($per_page = 15, $category_id = null, $statuses = [], $sort_by = 'id', $order_by = 'desc')
    {
        $query = $this->productModel
            ->join('product_language', 'products.id', 'product_language.product_id')
            ->join('order_items', 'products.id', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', 'orders.id')
            ->join('categories', 'products.main_category_id', 'categories.id')
            ->join('category_language', 'categories.id', 'category_language.category_id');

        $query = $query
            ->where('product_language.language_id', Session::get('language_id'))
            ->where('category_language.language_id', Session::get('language_id'))
            ->whereIn('orders.status', $statuses);

        $query = $category_id !== null ? $query->where('products.main_category_id', $category_id) : $query;

        return  $query->select(
            'products.id as id',
            'product_language.name as name',
            'products.sku as sku',
            'category_language.name as category_name',
            \DB::raw('CAST(SUM(order_items.quantity) as UNSIGNED) as sold_quantity'),
            \DB::raw('SUM(order_items.quantity * order_items.price) as total_price'),
            \DB::raw("date_format(order_items.created_at,'%Y-%m-%d') as date")
        )->groupBy('order_items.created_at', 'products.id', 'product_language.name', 'category_language.name')
            ->orderBy($sort_by, $order_by)
            ->paginate($per_page);
    }
}
