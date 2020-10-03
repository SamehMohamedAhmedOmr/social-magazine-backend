<?php

namespace Modules\Reports\Repositories;

use Illuminate\Support\Facades\Session;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Product;

class FinancialReportRepository extends LaravelRepositoryClass
{
    public $productsModel;

    public function __construct(Product $productsModel)
    {
        $this->productsModel = $productsModel;
    }

    public function index($per_page = 15, $search = null, $from = null, $to = null, $brand = null, $sort_by = 'id', $order_by = 'desc')
    {
        $data = $this->productsModel
            ->join('product_language', 'products.id', '=', 'product_language.product_id')
            ->join('order_items', 'products.id', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', 'orders.id');

        $data = isset($search)
            ? $data->where(function ($q) use ($search) {
                $q->where('product_language.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('products.sku', 'LIKE', '%' . $search . '%');
            })
            : $data;

        $data = $data->where('product_language.language_id', Session::get('language_id'));

        $data = $brand ? $data->where('products.brand_id', $brand) : $data;
        $data = $from ? $data->whereDate('orders.created_at', '>=', $from) : $data;
        $data = $to ? $data->whereDate('orders.created_at', '<=', $to) : $data;

        $data = $data->where('orders.is_restored', false)
            ->where('orders.deleted_at', null)
            ->select(
                'products.id',
                'products.sku',
                'product_language.name as name',
                'product_language.slug as slug',
                'product_language.description as description',
                \DB::raw('CAST(SUM(order_items.quantity) as UNSIGNED) as total_quantity'),
                \DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'),
                \DB::raw('SUM(order_items.quantity * order_items.buying_price) as total_cost'),
                \DB::raw('COUNT(order_items.order_id) as total_orders')
            )->groupBy('products.id', 'product_language.name',
                'product_language.slug', 'product_language.description')
            ->orderBy($sort_by, $order_by)
            ->paginate($per_page);

        return [
            'data' => $data->load('orders.orderItems'),
            'pagination' => $data
        ];
    }

}
