<?php

namespace Modules\Reports\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Reports\Repositories\EndOfDayReportRepository;
use Modules\WareHouse\Repositories\CMS\Order\OrderStatusRepository;

class EndOfDayReportService extends LaravelServiceClass
{
    protected $orderStatusRepository;
    public function __construct(EndOfDayReportRepository $repository, OrderStatusRepository $orderStatusRepository)
    {
        $this->repository = $repository;
        $this->orderStatusRepository = $orderStatusRepository;
    }

    public function index()
    {
        $statuses = $this->orderStatusRepository->all();

        $start_date = request('start_date');
        $end_date = request('end_date');
        $query = $this->repository->index($start_date, $end_date);
        $data = [];
        foreach ($statuses->pluck('key')->toArray() as $status) {
            $data[$status]['total_price'] = 0;
            $data[$status]['total_orders'] = 0;
        }

        $keyID = $statuses->pluck('key', 'id')->toArray();

        foreach ($query as $value) {
            $data[$keyID[$value->order_status_id]]['total_price'] = $value->total_price;
            $data[$keyID[$value->order_status_id]]['total_orders'] = $value->total_orders;
        }
        return ApiResponse::format(200, $data, 'Successful Query');
    }
}
