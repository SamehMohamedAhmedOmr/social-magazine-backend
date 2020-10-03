<?php

namespace Modules\Reports\Repositories;

use Illuminate\Support\Facades\Session;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\District;

class DistrictReportRepository extends LaravelRepositoryClass
{
    protected $districtModel;

    public function __construct(District $districtModel)
    {
        $this->districtModel = $districtModel;
    }

    public function ordersPerDistrict($statuses)
    {
        return $this->districtModel->join('address', 'address.district_id', 'districts.id')
            ->join('orders', 'orders.address_id', 'address.id')
            ->join('district_languages', 'districts.id', 'district_languages.district_id')
            ->where('district_languages.language_id', Session::get('language_id') ?? 1)
            ->whereIn('orders.status', $statuses)
            ->select(
                'districts.id',
                'district_languages.name',
                \DB::raw('COUNT(orders.id) as orders'),
                \DB::raw('SUM(`orders`.`total_price` + `orders`.`vat` - `orders`.`discount`) as `final_price`'),
                'orders.status'
            )->groupBy('status', 'id', 'name')->get();
    }
}
