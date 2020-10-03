<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\PurchaseOrder;
use Modules\WareHouse\Entities\Warehouse;

class PurchaseOrderRepository extends LaravelRepositoryClass
{
    public function __construct(PurchaseOrder $purchase_order_model)
    {
        $this->model = $purchase_order_model;
        $this->cache_key = 'purchase_orders';
    }
    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->model;

        $query = $query->with($this->relationShips(getLang()));

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function detachProduct($model)
    {
        $model->products()->detach();
    }

    public function attachProduct($model, $products_data)
    {
        $model->products()->attach($products_data);
    }

    public function relationShips($language_id = null)
    {
        if (isset($language_id)) {
            $lang_query =  function ($query) use ($language_id) {
                $query->where('language_id', $language_id);
            };

            $relationships = [
                'warehouse.language' => $lang_query,
                'warehouse.district.language' => $lang_query,
                'warehouse.district.country.language' => $lang_query,
                'company.language' => $lang_query,
                'products.languages' => $lang_query,
                'tax.language' => $lang_query,
                'products.languages.language',
                'tax.amountType', 'tax.taxType', 'shippingRule'
            ];
        } else {
            $relationships =  [
                'warehouse.language',  'warehouse.district.language', 'warehouse.district.country.language',
                'company.language','products.languages.language',
                'tax.language','tax.amountType', 'tax.taxType',
                'shippingRule'
            ];
        }

        return $relationships;
    }
}
