<?php

namespace Modules\Reports\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Brand;
use Modules\WareHouse\Entities\District;
use Modules\WareHouse\Entities\Order\Order;
use Modules\WareHouse\Entities\PurchaseOrder;

class DashboardReportRepository extends LaravelRepositoryClass
{
    protected $orderModel;

    protected $districtModel;

    protected $purchaseOrderModel;

    protected $brandModel;

    public function __construct(
        Order $orderModel,
        District $districtModel,
        PurchaseOrder $purchaseOrderModel,
        Brand $brandModel
    )
    {
        $this->orderModel = $orderModel;
        $this->districtModel = $districtModel;
        $this->purchaseOrderModel = $purchaseOrderModel;
        $this->brandModel = $brandModel;
    }

    public function ordersPerMonth()
    {
        $now_year = Carbon::now()->format('Y');
        return $this->orderModel->whereYear('created_at', $now_year)
            ->where('is_restored', false)
            ->get()->groupBy(function ($q) {
                return Carbon::parse($q->created_at)->format('m');
            });
    }

    public function ordersPerStatus($statuses)
    {
        return $this->orderModel
            ->select(
                'order_status_id',
                \DB::raw('SUM(total_price - discount + shipping_price + vat) as final_price')
            )
            ->where('is_restored', false)
            ->whereIn('order_status_id', $statuses)
            ->groupBy('order_status_id')->get();
    }

    public function ordersPerDistrict($statuses)
    {
        return $this->districtModel->join('address', 'address.district_id', 'districts.id')
            ->join('orders', 'orders.address_id', 'address.id')
            ->join('district_languages', 'districts.id', 'district_languages.district_id')
            ->where('district_languages.language_id', Session::get('language_id') ?? 1)
            ->whereIn('orders.order_status_id', $statuses)
            ->select(
                'districts.id',
                'district_languages.name',
                \DB::raw('COUNT(orders.id) as orders'),
                \DB::raw('SUM(`orders`.`total_price` + `orders`.`vat` - `orders`.`discount`) as `final_price`'),
                'orders.order_status_id'
            )->groupBy('order_status_id', 'id', 'name')->get();
    }

    public function ordersPerBrand($statuses)
    {
        return $this->brandModel
            ->join('brand_language', 'brands.id', 'brand_language.brand_id')
            ->join('products', 'products.brand_id', 'brands.id')
            ->join('order_items', 'order_items.product_id', 'products.id')
            ->join('orders', 'order_items.order_id', 'orders.id')
            ->whereIn('orders.order_status_id', $statuses)
            ->where('brand_language.language_id', Session::get('language_id') ?? 1)

            ->select(
                'brands.id',
                'brand_language.name',
                \DB::raw('SUM(order_items.quantity) as quantity'),
                \DB::raw('COUNT(DISTINCT  orders.id) as total_orders'),
                \DB::raw('SUM(`order_items`.`price` + `order_items`.`quantity`) as `total_price`'),
                'orders.order_status_id'
            )->groupBy('order_status_id', 'id', 'name')->get();
    }

    public function purchaseOrders()
    {
        return $this->purchaseOrderModel->select(
            \DB::raw('IFNULL(SUM(total_price - discount), 0) as total_price'),
            \DB::raw('COUNT(*) as total_purchase_orders')
        )->first();
    }
}
