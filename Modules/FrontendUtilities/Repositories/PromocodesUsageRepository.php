<?php


namespace Modules\FrontendUtilities\Repositories;

use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\FrontendUtilities\Entities\PromocodeUsage;

class PromocodesUsageRepository extends LaravelRepositoryClass
{
    protected $model;
    public function __construct(PromocodeUsage $promocodeUsage)
    {
        $this->model = $promocodeUsage;
        $this->cache = 'promocode_usage';
    }
}
