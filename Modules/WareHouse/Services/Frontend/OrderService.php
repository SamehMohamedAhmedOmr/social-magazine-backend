<?php


namespace Modules\WareHouse\Services\Frontend;

use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\Repositories\OrderRepository;
use Modules\WareHouse\Transformers\Frontend\Checkout\OrderResource;

class OrderService extends LaravelServiceClass
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index($user_id = null)
    {
        list($orders, $pagination) = parent::paginate($this->orderRepository, null, false, [
            'user_id' => \Auth::id()
        ]);

        $orders->load('orderItems.toppings');

        foreach ($orders as $order) {
            $items = [];
            foreach ($order->orderItems as $item) {
                if (isset($item->product)) {
                    $items [] = $item;
                }
            }
            $order->orderItems = $items;
        }

        $orders->load([
            'address',
            'paymentMethod.currentLanguage',
            'timeSection',
            'shipment'
        ]);

        return ApiResponse::format(200, OrderResource::collection($orders), null, $pagination);
    }

    public function show($id)
    {
        $order = $this->orderRepository->get($id, [
            'user_id' => \Auth::id()
        ]);

        $items = [];
        $order->load('orderItems.toppings');

        foreach ($order->orderItems as $item) {
            if (isset($item->product)) {
                $items [] = $item;
            }
        }
        $order->orderItems = $items;

        $order->load([
            'address',
            'paymentMethod.currentLanguage',
            'timeSection',
            'shipment',
        ]);

        return ApiResponse::format(200, OrderResource::make($order));
    }

    private function prepareOrdersForAdmins()
    {
        list($pagination_number, $sort_key, $sort_order, $conditions, $search_key) = Pagination::preparePaginationKeys(
            request('per_page'),
            request('sort_key'),
            request('sort_order'),
            request('search_key'),
            request('is_active'),
            false
        );

        $user = \Auth::user();

        $warehouses_id = [];

        $admin = $user->admin;
        if ($admin) {
            $warehouses =$user->admin->warehouses;
            if (count($warehouses)) {
                $warehouses_id = $warehouses->pluck('id')->toArray();
            }
        }

        if (request('user_id')) {
            $conditions = [
                'user_id' => request('user_id')
            ];
        }

        $orders = $this->orderRepository->paginate(
            $pagination_number ,
            $conditions,
            $search_key,
            $sort_key,
            $sort_order,
            null,
            $warehouses_id
        );

        $pagination = Pagination::preparePagination($orders);
        return [$orders, $pagination];
    }
}
