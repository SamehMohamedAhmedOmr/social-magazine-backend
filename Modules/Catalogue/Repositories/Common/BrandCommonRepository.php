<?php

namespace Modules\Catalogue\Repositories\Common;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Base\Repositories\Classes\LaravelRepositoryClass;
use Modules\Catalogue\Entities\Brand;
use Modules\Catalogue\Entities\BrandLanguage;

class BrandCommonRepository extends LaravelRepositoryClass
{
    use DispatchesJobs;

    protected $brand_language_model;

    public function __construct(Brand $brand_model, BrandLanguage $brand_language_model)
    {
        $this->model = $brand_model;
        $this->brand_language_model = $brand_language_model;
        $this->cache_key = 'brand';
    }
}
