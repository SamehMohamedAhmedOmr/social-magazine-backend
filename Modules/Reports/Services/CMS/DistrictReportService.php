<?php

namespace Modules\Reports\Services\CMS;

use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\Reports\Repositories\DistrictReportRepository;
use Modules\WareHouse\Repositories\CMS\Order\OrderStatusRepository;

class DistrictReportService extends LaravelServiceClass
{
    protected $orderStatusRepository;

    public function __construct(DistrictReportRepository $repository, OrderStatusRepository $orderStatusRepository)
    {
        $this->repository = $repository;
        $this->orderStatusRepository = $orderStatusRepository;
    }

    public function index()
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

        return ApiResponse::format(200, $data, 'Successful Query');
    }
}
