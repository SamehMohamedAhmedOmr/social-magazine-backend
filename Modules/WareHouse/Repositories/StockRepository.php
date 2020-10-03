<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Stock;
use Modules\WareHouse\Entities\Warehouse;

class StockRepository extends LaravelRepositoryClass
{
    public function __construct(Stock $stock_model)
    {
        $this->model = $stock_model;
        $this->cache_key = 'stock';
    }

    // Should be in product Repo
    public function whereIn($column, $conditions)
    {
        return $this->model->whereIn($column, $conditions)->get();
    }

    public function createMany($data)
    {
        return $this->model->insert($data);
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->model;

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }
}
