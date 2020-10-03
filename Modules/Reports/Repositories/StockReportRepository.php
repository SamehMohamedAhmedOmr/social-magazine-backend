<?php

namespace Modules\Reports\Repositories;

use Illuminate\Support\Facades\Session;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Product;
use Modules\WareHouse\Entities\Stock;

class StockReportRepository extends LaravelRepositoryClass
{
    public $productModel;

    public $stockModel;

    public function __construct(Product $product, Stock $stock)
    {
        $this->productModel = $product;
        $this->stockModel = $stock;
    }

    public function index(
        $warehouses,
        $stock_qty = null,
        $category_id = null,
        $min_price = null,
        $max_price = null,
        $per_page = 15,
        $sort_by = 'id',
        $order_by = 'desc'
    )
    {
        $query = $this->productModel
            ->join('product_language', 'products.id', 'product_language.product_id')
            ->join('product_warehouses', 'products.id', 'product_warehouses.product_id')
            ->join('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
            ->join('warehouse_languages', 'warehouses.id', 'warehouse_languages.warehouse_id')
            ->join('categories', 'products.main_category_id', 'categories.id')
            ->join('category_language', 'categories.id', 'category_language.category_id')
            ->join('product_price_list', 'products.id', 'product_price_list.product_id')
            ->join('price_lists', 'product_price_list.price_list_id', 'price_lists.id');
        ;

        $query = $query
            ->where('product_language.language_id', Session::get('language_id'))
            ->where('warehouse_languages.language_id', Session::get('language_id'))
            ->where('category_language.language_id', Session::get('language_id'))
            ->whereIn('product_warehouses.warehouse_id', $warehouses)
            ->where('price_lists.key', 'STANDARD_SELLING_PRICE');

        $query = $stock_qty !== null
            ? ($stock_qty === '>'
                ? $query->where('product_warehouses.projected_quantity', '>', 0)
                : $query->where('product_warehouses.projected_quantity', '=', 0))
            : $query;

        $query = $category_id !== null ? $query->where('products.main_category_id', $category_id) : $query;
        $query = $max_price !== null ? $query->where('product_price_list.price', '<=', $max_price) : $query;
        $query = $min_price !== null ? $query->where('product_price_list.price', '>=', $min_price) : $query;


        return $query->select(
            'products.id as id',
            'product_language.name as name',
            'products.sku as sku',
            'product_price_list.price as price',
            'product_warehouses.projected_quantity as stock_qty',
            'warehouse_languages.name as warehouse_name',
            'category_language.name as category_name'
        )->orderBy($sort_by, $order_by)->paginate($per_page);
    }

    public function import(
        $per_page = 15,
        $from_warehouse = null,
        $to_warehouse = null,
        $date = null,
        $sort_by = 'id',
        $order_by = 'desc'
    )
    {
        $query = $this->stockModel
            ->join('product_language', 'stocks.product_id', 'product_language.product_id')
        ->where('product_language.language_id', Session::get('language_id'));

        $query = isset($date) ? $query->whereDate('stocks.created_at', $date) : $query;
        $query = isset($from_warehouse) && $from_warehouse != [] ? $query->whereIn('stocks.from_warehouse', $from_warehouse) : $query;
        $query = isset($to_warehouse)  && $to_warehouse != [] ? $query->whereIn('stocks.to_warehouse', $to_warehouse) : $query;
        return $query->select('stocks.*', 'product_language.name as product_name')
            ->orderBy($sort_by, $order_by)
            ->paginate($per_page);
    }

    public function deleteLog($id)
    {
        return $this->stockModel->findOrFail($id)->delete();
    }
}
