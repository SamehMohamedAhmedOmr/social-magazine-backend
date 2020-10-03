<?php

namespace Modules\WareHouse\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\WareHouse\Entities\PaymentEntry;

class PaymentEntryRepository extends LaravelRepositoryClass
{
    public function __construct(PaymentEntry $payment_entry)
    {
        $this->model = $payment_entry;
        $this->cache_key = 'payment_entry';
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
            'PaymentEntryType'
        ];
    }
}
