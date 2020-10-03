<?php

namespace Modules\Settings\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\PriceListType;

class PriceListTypeRepository extends LaravelRepositoryClass
{
    public function __construct(PriceListType $price_list_type)
    {
        $this->model = $price_list_type;
        $this->cache_key = 'price_list_type';
    }

    public function paginate($per_page = 15, $conditions = [], $search_keys = [], $sort_key = 'id', $sort_order = 'asc', $lang = null)
    {
        return parent::paginate($this->model, $per_page, $conditions, $sort_key, $sort_order);
    }
}
