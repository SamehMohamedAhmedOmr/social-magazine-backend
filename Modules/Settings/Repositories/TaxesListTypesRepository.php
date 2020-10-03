<?php

namespace Modules\Settings\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Settings\Entities\TaxesListType;

class TaxesListTypesRepository extends LaravelRepositoryClass
{
    public function __construct(TaxesListType $taxes_list_type)
    {
        $this->model = $taxes_list_type;
        $this->cache_key = 'taxes_lists_type';
    }
}
