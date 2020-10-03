<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\PurchaseReceipt;
use Modules\WareHouse\Entities\Warehouse;

class PurchaseReceiptRepository extends LaravelRepositoryClass
{
    public function __construct(PurchaseReceipt $purchase_receipt_model)
    {
        $this->model = $purchase_receipt_model;
        $this->cache_key = 'purchase_receipt';
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->model;

        $query = $query->with($this->relationShips(getLang()));

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function getPurchaseReceiptIds($purchase_order_id)
    {
        return $this->model->where('purchase_order_id', $purchase_order_id)->pluck('id');
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
                'company.language' => $lang_query,
                'products.languages' => $lang_query,
                'tax.language' => $lang_query,
                'products.languages.language',
                'tax.amountType', 'tax.taxType', 'shippingRule'
            ];
        } else {
            $relationships =  [
                'company.language','products.languages.language',
                'tax.language','tax.amountType', 'tax.taxType',
                'shippingRule'
            ];
        }

        return $relationships;
    }
}
