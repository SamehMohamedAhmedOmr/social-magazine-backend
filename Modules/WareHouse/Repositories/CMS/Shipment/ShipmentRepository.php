<?php

namespace Modules\WareHouse\Repositories\CMS\Shipment;

use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\WareHouse\Entities\Shipment\Shipment;

class ShipmentRepository extends LaravelServiceClass
{
    protected $model;

    public function __construct(Shipment $shipment)
    {
        $this->model = $shipment;
    }

    public function create($data)
    {
        return \DB::transaction(function () use ($data){
            return $this->model->create($data);
        });
    }

    public function get($value, $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $with != []
            ? $data->with($with)
            : $data;

        $data = $column
            ? $data->where($column, $value)
            : $data;

        $data = $data->firstOrFail();

        return $data;
    }

    public function updateShipmentModel($model, array $attributes)
    {
        $model = $model->update($attributes);
        return $model;
    }

    public function updateShipment(array $data, $value, $column = 'id')
    {
        $response = $this->model->where($column, $value)->firstOrFail();
        $response->update($data);
        return $response;
    }
}
