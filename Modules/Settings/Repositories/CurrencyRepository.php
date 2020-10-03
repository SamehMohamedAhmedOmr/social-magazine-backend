<?php

namespace Modules\Settings\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\Currency;

class CurrencyRepository extends LaravelRepositoryClass
{
    public function __construct(Currency $currency)
    {
        $this->model = $currency;
        $this->cache_key = 'currency';
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        return parent::paginate($this->model, $per_page, $conditions, $sort_key, $sort_order);
    }
}
