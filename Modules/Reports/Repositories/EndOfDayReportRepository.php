<?php

namespace Modules\Reports\Repositories;

use Carbon\Carbon;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Order\Order;

class EndOfDayReportRepository extends LaravelRepositoryClass
{
    public $orderModel;

    public function __construct(Order $order)
    {
        $this->orderModel = $order;
    }

    public function index($start_date = null, $end_date = null)
    {
        $query = ($start_date === null && $end_date === null)
            ? $this->orderModel->whereDate('created_at', Carbon::today()->format('Y-m-d'))
            : $this->orderModel->query();
        $query = $start_date !== null ? $query->whereDate('created_at', '>=', $start_date) : $query;
        $query = $end_date !== null ? $query->whereDate('created_at', '<=', $end_date) : $query;

        $query = $query->select(
            'order_status_id',
            \DB::raw('SUM(total_price + vat - discount) as total_price'),
            \DB::raw('COUNT(*) as total_orders')
        )->groupBy('order_status_id')
            ->get();
        return $query;
    }

    public function getStatuses()
    {
        return array_values(Order::STATUSES);
    }

    public function getStatus($status_number)
    {
        return Order::STATUSES[$status_number];
    }
}
