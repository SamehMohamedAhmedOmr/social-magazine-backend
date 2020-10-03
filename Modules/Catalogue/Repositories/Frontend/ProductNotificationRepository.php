<?php

namespace Modules\Users\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\ProductNotification;

class ProductNotificationRepository extends LaravelRepositoryClass
{
    public function __construct(ProductNotification $productNotification)
    {
        $this->model = $productNotification;
    }

    public function createOrUpdate($conditions = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $data->first();

        if (!$data){
            $this->create($conditions);
        }

        return $data;
    }

    public function getData($products = [])
    {
        return $this->model->whereIn('product_id', $products)
            ->where('warehouse_id', \Session::get('to_warehouse'))
            ->get();
    }

    public function deleteBulk($products = [])
    {
        return $this->model->whereIn('product_id', $products)
            ->where('warehouse_id', \Session::get('to_warehouse'))
            ->delete();
    }
}
