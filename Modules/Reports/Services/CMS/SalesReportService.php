<?php

namespace Modules\Reports\Services\CMS;

use Illuminate\Support\Facades\Session;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Reports\Repositories\SalesReportRepository;
use Modules\WareHouse\Repositories\CMS\Order\OrderStatusRepository;
use ProductHelper;
use Pagination;

class SalesReportService extends LaravelServiceClass
{
    protected $repository;
    protected $orderStatusRepository;

    public function __construct(SalesReportRepository $salesReportRepository, OrderStatusRepository $orderStatusRepository)
    {
        $this->repository = $salesReportRepository;
        $this->orderStatusRepository = $orderStatusRepository;
    }

    public function index()
    {
        $per_page = request('per_page') ?? 15;
        $category_id = request('category_id') !== null ? request('category_id') : null;
        $order_by = request('order_by') && in_array(strtolower(request('order_by')), ['asc', 'desc'])
            ? request()->order_by : 'desc';
        $sort_by = request('sort_by') && in_array(request('sort_by'), ['name', 'sold_quantity', 'total_price', 'date'])
            ? request('sort_by') : 'name';

        $statuses = $this->orderStatusRepository->whereIn(['CONFIRMED', 'DELIVERED']);

        $products = $this->repository->index($per_page, $category_id, $statuses->pluck('key')->toArray(), $sort_by, $order_by);

        $pagination = Pagination::preparePagination($products);

        return ApiResponse::format(200, $products->items(), 'Successful Query', $pagination);
    }
}
