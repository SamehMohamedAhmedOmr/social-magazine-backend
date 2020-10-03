<?php

namespace Modules\WareHouse\Repositories\CMS\Shipment;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Shipment\ShippingCompany;

class CompanyRepository extends LaravelRepositoryClass
{
    public function __construct(ShippingCompany $shipping_company)
    {
        $this->model = $shipping_company;
        $this->cache_key = 'shipping_companies';
    }

    public function index(
        $search = null,
        $sort_by = 'id',
        $order_by= 'desc',
        $sort_language= ''
    ) {
        $query = $this->query(
            $search,
            $sort_by,
            $order_by,
            $sort_language
        );
        return $query->get();
    }

    public function paginate(
        $per_page = 15,
        $conditions = [],
        $search = null,
        $sort_by = 'id',
        $order_by= 'desc',
        $sort_language= ''
    ) {
        $query = $this->query(
            $search,
            $sort_by,
            $order_by,
            $sort_language
        );
        return $query->paginate($per_page);
    }

    protected function query(
        $search,
        $sort_by,
        $order_by,
        $sort_language
    )
    {
        $query = $this->model;

        $query = $search['search_key'] !== null
            ? $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search['search_key'] . '%')
                    ->orWhere('id', 'LIKE', '%' . $search['search_key'] . '%');
            })
            : $query;

        $query = $search['is_active'] !== null
            ? $query->where('is_active', $search['is_active'])
            : $query;
        $query = $search['trashed'] !== null
            ? ($search['trashed'] == true ? $query->where('deleted_at', '!=', null)
                :  $query->where('deleted_at', null))
            : $query->where('deleted_at', null);
        return $query;
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

        $data = $data->withTrashed()->firstOrFail();

        return $data;
    }

    public function update($value, $attributes = [], $conditions = [], $column = 'id', $with = [])
    {
        $data = $conditions != []
            ? $this->model->where($conditions)
            : $this->model;

        $data = $column
            ? $data->where($column, $value)
            : $data;
        $data = $data->withTrashed()->firstOrFail();

        $data->update($attributes);

        return $data->load($with);
    }
}
