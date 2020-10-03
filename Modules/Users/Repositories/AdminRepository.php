<?php

namespace Modules\Users\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Users\Entities\Admin;

class AdminRepository extends LaravelRepositoryClass
{
    public function __construct(Admin $admin)
    {
        $this->model = $admin;
    }

    public function syncCountries($model, $countries)
    {
        $model->countries()->detach();
        $model->countries()->attach($countries);
    }


    public function syncWarehouses($model, $warehouses)
    {
        $model->warehouses()->detach();
        $model->warehouses()->attach($warehouses);
    }


    public function adminsByWarehouse($warehouse_id)
    {
        return $this->model->join('admin_warehouses', 'admin.id', 'admin_warehouses.admin_id')
            ->where('admin_warehouses.warehouse_id', $warehouse_id)
            ->select('admin.*')
            ->get();
    }
}
