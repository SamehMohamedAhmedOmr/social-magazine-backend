<?php

namespace Modules\Catalogue\Repositories\Common;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\ToppingMenu;
use Modules\Catalogue\Entities\ToppingMenuLanguage;

class ToppingMenuCommonRepository extends LaravelRepositoryClass
{
    protected $topping_language_model;

    public function __construct(ToppingMenu $topping_model, ToppingMenuLanguage $topping_language_model)
    {
        $this->model = $topping_model;
        $this->topping_language_model = $topping_language_model;
        $this->cache_key = 'topping';
    }
}
