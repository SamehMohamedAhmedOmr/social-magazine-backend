<?php

namespace Modules\Settings\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\TaxesListAmountType;
use Modules\Settings\Entities\TaxesListType;

class TaxesListAmountTypeRepository extends LaravelRepositoryClass
{
    public function __construct(TaxesListAmountType $taxes_list_amount_type)
    {
        $this->model = $taxes_list_amount_type;
        $this->cache_key = 'taxes_list_amount_type';
    }
}
