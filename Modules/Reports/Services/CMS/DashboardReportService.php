<?php

namespace Modules\Reports\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Reports\Repositories\DashboardReportRepository;
use Modules\WareHouse\Repositories\CMS\Order\OrderStatusRepository;

class DashboardReportService extends LaravelServiceClass
{
    protected $orderStatusRepository;

    public function __construct(DashboardReportRepository $repository, OrderStatusRepository $orderStatusRepository)
    {
        $this->repository = $repository;
        $this->orderStatusRepository = $orderStatusRepository;
    }

    public function index()
    {
        $data =  [
            'orders_months' => $this->salesPerMonth(),
            'orders_statuses' => $this->salesPerStatus(),
            'orders_districts' => $this->salesPerDistrict(),
            'orders_brands' => $this->salesPerBrand(),
            'purchase_orders' => $this->purchaseOrder(),
        ];

        return ApiResponse::format(200, $data, 'Successful Query');
    }

    public function salesPerMonth()
    {
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = [];
            $data[$i]['total_orders'] = 0;
            $data[$i]['total_money'] = 0;
        }
        $query_response = $this->repository->ordersPerMonth();
        foreach ($query_response as $month => $orders) {
            $data[(integer)$month]['total_orders'] = count($orders);
            $data[(integer)$month]['total_money'] = 0;

            foreach ($orders as $order) {
                $data[(integer)$month]['total_money'] += $order->total_price + $order->vat - $order->discount;
            }
        }

        return $data;
    }

    public function salesPerStatus()
    {
        $data = [];

        $statuses = $this->orderStatusRepository->whereIn(['CONFIRMED', 'DELIVERED']);

        $query_response = $this->repository->ordersPerStatus($statuses->pluck('id')->toArray());

        $keyID = $statuses->pluck('key', 'id')->toArray();

        foreach ($query_response as $order) {
            if ($keyID[$order->order_status_id] === 'DELIVERED') {
                $data['delivered_orders'] = $order->final_price;
            }
            if ($keyID[$order->order_status_id] === 'CONFIRMED') {
                $data['confirmed_orders'] = $order->final_price;
            }
        }
        return $data;
    }

    public function purchaseOrder()
    {
        return $this->repository->purchaseOrders();
    }

    public function salesPerDistrict()
    {
        $data = [];
        $statuses = $this->orderStatusRepository->whereIn(['CONFIRMED', 'DELIVERED']);
        $query_response = $this->repository->ordersPerDistrict($statuses->pluck('id')->toArray());
        $keyID = $statuses->pluck('key', 'id')->toArray();

        $i = -1;
        foreach ($query_response as $district) {
            $key = array_search($district->id, array_column($data, 'id'));

            if ($key === false) {
                $i++;
                $key = $i;
            }

            $data[$key]['id'] = $district->id;
            $data[$key]['name'] = $district->name;
            if ($keyID[$district->status] === 'DELIVERED') {
                $data[$key]['statuses'][] = ['status' => 'delivered', 'total_money' => $district->final_price,
                    'total_orders' => $district->orders];
            }
            if ($keyID[$district->status] === 'CONFIRMED') {
                $data[$key]['statuses'][] = ['status' => 'confirmed', 'total_money' => $district->final_price,
                    'total_orders' => $district->orders];
            }
        }

        return $data;
    }

    public function salesPerBrand()
    {
        $data = [];
        $statuses = $this->orderStatusRepository->whereIn(['CONFIRMED', 'DELIVERED']);
        $query_response = $this->repository->ordersPerBrand($statuses->pluck('id')->toArray());
        $keyID = $statuses->pluck('key', 'id')->toArray();

        $i = -1;
        foreach ($query_response as $district) {
            $key = array_search($district->id, array_column($data, 'id'));

            if ($key === false) {
                $i++;
                $key = $i;
                $data[$key]['statuses'] = [];
            }
            $data[$key]['id'] = $district->id;
            $data[$key]['name'] = $district->name;
            $data[$key]['statuses'] = [];
            if ($keyID[$district->status] === 'DELIVERED') {
                $data[$key]['statuses'][] = ['status' => 'delivered',
                    'total_money' => $district->total_price,
                    'quantity' => (integer)$district->quantity,
                    'total_orders' => $district->total_orders
                ];
            }
            if ($keyID[$district->status] === 'CONFIRMED') {
                $data[$key]['statuses'][] = ['status' => 'confirmed',
                    'total_money' => $district->total_price,
                    'quantity' => (integer)$district->quantity,
                    'total_orders' => $district->total_orders
                ];
            }
        }

        return $data;
    }
}
