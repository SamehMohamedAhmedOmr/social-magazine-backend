<?php

namespace Modules\Reports\Services\CMS;

use Carbon\Carbon;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Reports\Repositories\FinancialReportRepository;
use Pagination;

class FinancialReportService extends LaravelServiceClass
{
    protected $repository;

    public function __construct(FinancialReportRepository $financialReportRepository)
    {
        $this->repository = $financialReportRepository;
    }

    public function index()
    {
        $per_page = request('per_page') ?? 15;

        $search = request('search_key');

        $brand = request('brand') ?? null;

        $date_from = request('date_from') ? Carbon::createFromFormat('Y-m-d', request('date_from')) : null;
        $date_to = request('date_to') ? Carbon::createFromFormat('Y-m-d', request('date_to')) : null;

        $from = request('date_from') &&  $date_from !== false ? $date_from->format('Y-m-d') : null;
        $to = request('date_to') &&  $date_to !== false ? $date_to->format('Y-m-d') : null;

        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['name', 'total_quantity', 'total_sales', 'total_orders'])
            ? request('sort_by') : 'name';

        $products = $this->repository->index($per_page, $search, $from, $to, $brand, $sort_by, $order_by);

        $pagination = Pagination::preparePagination($products['pagination']);

        $data = $this->returnedData($products['data']);

        return ApiResponse::format(200, $data, 'Successful Query', $pagination);
    }

    protected function returnedData($data)
    {
        $products = [];
        $statistics = [
            'total_product' => count($data),
            'total_quantity' => $data->sum('total_quantity'),
            'total_orders' => $data->sum('total_orders'),
            'total_sales' => $data->sum('total_sales'),
            'total_cost' => $data->sum('total_cost'),
            'total_profit_before_discount' => 0,
            'total_profit_after_discount' => 0,
            'total_discount' => 0
        ];
        foreach ($data as &$item) {
            $item['profit_before_discount'] = $item['total_sales'];
            $item['discount'] = 0;
            foreach ($item->orders as $order) {
                $items_quantity = 0;

                foreach ($order->orderItems as $order_item) {
                    $items_quantity += $order_item->quantity;
                }
                $item['discount'] += (($order->discount + $order->loyality_discount) / $items_quantity);
            }
            $item['discount'] = round($item['discount'], 2);
            $item['profit_after_discount'] = round($item['profit_before_discount'] - $item['discount'], 2);

            $value = $item;
            unset($value['orders']);
            $products[] = $value;
            $statistics['total_profit_before_discount'] = round($statistics['total_profit_before_discount'] +$item['profit_before_discount'], 2);
            $statistics['total_profit_after_discount'] = round($statistics['total_profit_after_discount'] + $item['profit_after_discount'], 2);
            $statistics['total_discount'] = round($statistics['total_discount'] + $item['discount'], 2);
        }

        return ['products' => $products, 'statistics' => $statistics];
    }
}
