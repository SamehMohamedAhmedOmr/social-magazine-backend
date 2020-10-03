<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Facade\LanguageFacade;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\Warehouse;

class WarehouseRepository extends LaravelRepositoryClass
{

    public function __construct(Warehouse $warehouse_model)
    {
        $this->model = $warehouse_model;
        $this->cache_key = 'warehouse';
    }

    public function detachProductWarehouse($model, $products)
    {
        $model->products()->detach($products);
    }

    public function attachProductWarehouse($model, $products_data)
    {
        $model->products()->attach($products_data);
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->filtering($search_keys);

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function all($conditions = [], $search_keys = null)
    {
        $query = $this->filtering($search_keys);

        return $query->where($conditions)->get();
    }

    private function filtering($search_keys){

        $query = $this->model;

        if ($search_keys) {
            $query = $query->where(function ($q) use ($search_keys){

                $where_conditions = ($search_keys) ? [
                    ['warehouse_languages.name', 'LIKE', '%' . $search_keys . '%']
                ] : [];

                $or_where_conditions = ($search_keys) ? [
                    ['description', 'LIKE', '%' . $search_keys . '%']
                ] : [];

                $q = LanguageFacade::loadLanguage($q, \Session::get('language_id'), 'language', $search_keys,
                    $where_conditions, $or_where_conditions);

                $q->orWhere('id', 'LIKE', '%'.$search_keys.'%')
                    ->orWhere('warehouse_code', 'LIKE', '%'.$search_keys.'%');
            });
        }

        return $query;
    }

    public function getDefault()
    {
        return $this->model->withoutGlobalScopes()->where('default_warehouse', 1)->with('products')->first();
    }

    public function whereIn($ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

}
