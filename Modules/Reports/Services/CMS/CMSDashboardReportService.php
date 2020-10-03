<?php

namespace Modules\Reports\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Reports\Transformers\CartDashboardResource;
use Modules\Reports\Transformers\CmsDashboardResource;
use Modules\Reports\Transformers\OrderDashboardResource;
use Modules\Users\Repositories\ClientRepository;
use Modules\WareHouse\Repositories\CartItemRepository;
use Modules\WareHouse\Repositories\CartRepository;
use Modules\WareHouse\Repositories\CMS\Order\OrderStatusRepository;
use Modules\WareHouse\Repositories\OrderRepository;

class CMSDashboardReportService extends LaravelServiceClass
{
    protected $order_repo;
    protected $client_repo;
    protected $cartItem_repo;
    protected $cart_repo;
    protected $orderStatusRepository;

    public function __construct(
        OrderRepository $order_repo,
        ClientRepository $client_repo,
        CartRepository $cart_repo,
        CartItemRepository $cartItem_repo,
        OrderStatusRepository $orderStatusRepository
    )
    {
        $this->order_repo = $order_repo;
        $this->client_repo = $client_repo;
        $this->cart_repo = $cart_repo;
        $this->cartItem_repo = $cartItem_repo;
        $this->orderStatusRepository = $orderStatusRepository;
    }

    public function index()
    {
        $orders_statistics = $this->prepareOrderStatistics();

        $cart_statistics = $this->prepareCartsStatistics();

        $client_counts = $this->client_repo->getClientLastMonth();

        $cms_dashboard_resource = new CmsDashboardResource($orders_statistics, $cart_statistics, $client_counts);

        return ApiResponse::format(200, $cms_dashboard_resource);
    }

    public function prepareOrderStatistics()
    {
        $orders = $this->order_repo->all();

        $canceled_status = $this->orderStatusRepository->get('CANCELLED', [], 'key', []);
        $delivered_status = $this->orderStatusRepository->get('DELIVERED', [], 'key', []);

        $canceled_orders = $orders->where('order_status_id', $canceled_status->id);

        $delivered_orders = $orders->where('order_status_id', $delivered_status->id);

        $pending_orders = $orders->whereNotIn('order_status_id', [
            $canceled_status->id,
            $delivered_status->id
        ]);


        list($canceled_orders_count, $canceled_orders_total_price) = $this->calculateTotalAndPrice($canceled_orders);
        list($delivered_orders_count, $delivered_orders_total_price) = $this->calculateTotalAndPrice($delivered_orders);
        list($pending_orders_count, $pending_orders_total_price) = $this->calculateTotalAndPrice($pending_orders);


        $total_order_counts = $canceled_orders_count + $delivered_orders_count + $pending_orders_count;

        $total_order_price = $canceled_orders_total_price + $delivered_orders_total_price + $pending_orders_total_price;


        return new OrderDashboardResource(
            $canceled_orders_count,
            $canceled_orders_total_price,
            $delivered_orders_count,
            $delivered_orders_total_price,
            $pending_orders_count,
            $pending_orders_total_price,
            $total_order_counts,
            $total_order_price
        );
    }

    public function prepareCartsStatistics()
    {
        $cart_items = $this->cartItem_repo->all();

        $carts = $cart_items->pluck('cart_id');

        $carts = $carts->unique()->toArray();


        $pending_carts_count = $this->cart_repo->getPendingCart($carts);

        $pending_cart_items_count = $cart_items->count();

        return new CartDashboardResource($pending_cart_items_count, $pending_carts_count);
    }

    public function calculateTotalAndPrice($orders)
    {
        $count = $orders->count();

        $total_price = 0;

        foreach ($orders as $order) {
            $total_order_price = ($order->total_price + $order->shipping_price + $order->vat) - $order->discount;

            $total_price += $total_order_price;
        }

        return [$count, $total_price];
    }
}
