<?php


namespace Modules\WareHouse\Services\CMS\Order;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Base\Facade\Pagination;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\ExcelExports\OrderExport;
use Modules\WareHouse\Repositories\CMS\Order\OrderRepository;
use Modules\WareHouse\Services\CMS\Exports\OrderExportService;
use Modules\WareHouse\Transformers\CMS\Order\OrderResource;

class OrderService extends LaravelServiceClass
{
    private $orderRepository;
    private $orderExportService;

    public function __construct(OrderRepository $orderRepository,
                                OrderExportService $orderExportService)
    {
        $this->orderRepository = $orderRepository;
        $this->orderExportService = $orderExportService;
    }

    public function index($user_id = null)
    {
        list($orders, $pagination) = $this->prepareOrdersForAdmins();

        $orders->load([
            'orderItems.toppings.currentLanguage',
            'orderItems.product.currentLanguage',
            'orderItems.product.favorites',
        ]);

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
            'shipment',
            'user.client',
            'loyality',
            'status.languages'
        ]);

        return ApiResponse::format(200, OrderResource::collection($orders), null, $pagination);
    }

    public function show($id, $user_id = null)
    {

        $order = $this->orderRepository->get($id);

        $items = [];

        $order->load([
            'orderItems.toppings.currentLanguage',
            'orderItems.product.currentLanguage',
            'orderItems.product.favorites',
        ]);

        foreach ($order->orderItems as $item) {
            if (isset($item->product)) {
                $items [] = $item;
            }
        }
        $order->orderItems = $items;

        $order->load([
            'address.district.language',
            'address.district.parentDistrict.language',
            'paymentMethod.currentLanguage',
            'timeSection',
            'shipment',
            'loyality',
            'user.client',
            'status.languages'
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

    public function exportOrders(){
        try {
            $date = Carbon::now();
            $pre_path = 'public/Excel/Order/';
            //$path = 'Orders-Reports-'.$date->toDateString().'-'.$date->hour.'h-'.$date->minute.'m';
            $path = 'Orders-Reports';
            $extension = '.xlsx';
            $full_path = $pre_path . $path . $extension;

            Excel::store(new OrderExport($this->orderExportService), $full_path);

            $order_path = getFilePath('Excel/Order/', $path . $extension);

            return ApiResponse::format(200, [
                'orders' => $order_path
            ], null);

        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
        }
    }



}
