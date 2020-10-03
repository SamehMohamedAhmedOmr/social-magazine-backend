<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\PurchaseInvoice;
use Modules\WareHouse\Entities\Warehouse;

class PurchaseInvoicesRepository extends LaravelRepositoryClass
{
    public function __construct(PurchaseInvoice $purchase_invoice_model)
    {
        $this->model = $purchase_invoice_model;
        $this->cache_key = 'purchase_invoice_model';
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        $query = $this->model;

        $query = $query->with($this->relationShips());

        return parent::paginate($query, $per_page, $conditions, $sort_key, $sort_order);
    }

    public function relationShips()
    {
        return [
            'paymentEntry.PaymentEntryType'
        ];
    }
}
